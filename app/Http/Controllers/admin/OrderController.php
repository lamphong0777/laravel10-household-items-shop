<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Province;
use App\Models\ReceiptDetail;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // get orders
        $orders = Order::latest();
        $sort_order = '';

        if (!empty($request->searchText)) {
            $orders = Order::where('id', $request->searchText);
        }
        if (!empty($request->sort_order)) {
            $sort_order = $request->sort_order;
            if ($sort_order != 'all') {
                $orders = Order::where('order_status', $request->sort_order);
            }
        }

        // Lọc theo ngày
        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $orders->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        $orders = $orders->paginate(10);
        return view('admin.order.index', compact('orders', 'sort_order'));
    }

    public function create() {}

    public function store(Request $request) {}

    public function show($id)
    {
        // get order details
        $order = Order::find($id);
        $order_items = OrderItem::where('order_id', $id)->get();
        $province = Province::find($order->province_id);
        $district = District::find($order->district_id);
        $ward = Ward::find($order->ward_id);

        return view('admin.order.show', compact('order', 'order_items', 'province', 'district', 'ward'));
    }

    public function edit($id) {}

    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // Validation rules
        $dataRequest = [
            'status' => 'required',
            'shipped_date' => 'required_if:status,shipped',
            'delivered_date' => 'required_if:status,delivered',
        ];

        // Add additional rule for COD payment method
        if ($order->payment_method === 'cod') {
            $dataRequest['paid_date'] = 'required_if:status,delivered';
        }

        $validator = Validator::make($request->all(), $dataRequest, [
            'shipped_date.required_if' => 'Ngày vận chuyển không hợp lệ',
            'paid_date.required_if' => 'Ngày thanh toán không hợp lệ',
            'delivered_date.required_if' => 'Ngày giao hàng không hợp lệ',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Prepare data for update
        $updateData = ['order_status' => $request->status];

        if ($request->status === 'shipped') {
            $updateData['shipped_date'] = $request->shipped_date;
        }

        if ($request->status === 'delivered') {
            $updateData['delivered_date'] = $request->delivered_date;

            if ($order->payment_method === 'cod') {
                $updateData['paid_date'] = $request->paid_date;
            }
        }
        if ($request->status === 'cancelled') {
            // Hủy đơn, không hoàn tiền online
            if($order->order_status == 'shipped' || $order->order_status == 'delivered'){
                $request->session()->flash('error', 'Không thể hủy đơn hàng.');
                return response()->json([
                    'status' => true,
                    'message' => 'Order was shipped'
                ]);
            }
            $this->cancelOrder($id);
        }
        // Update order
        $order->update($updateData);

        $request->session()->flash('success', 'Cập nhật trạng thái đơn hàng thành công.');

        return response()->json([
            'status' => true,
            'message' => 'Order updated successfully'
        ]);
    }

    public function destroy($id) {}

    public function sendOrderEmail(Request $request, $id)
    {
        orderEmail($id, $request->user_type);
        return response()->json([
            'status' => true,
            'message' => 'Hóa đơn được gủi thành công!'
        ]);
    }

    public function cancelOrder($orderId)
    {
        try {
            $order = Order::find($orderId);
            if ($order->order_status == 'pending') {
                DB::beginTransaction();
                // $order->update([
                //     'order_status' => 'cancelled'
                // ]);
                // Lấy danh sách sản phẩm
                $orderItems = OrderItem::where('order_id', $orderId)->get();
                foreach ($orderItems as $orderItem) {
                    // Cập nhật số lượng sản phẩm trong bảng products
                    $product = Product::find($orderItem->product_id);
                    // Cập nhật lại số lượng sản phẩm
                    $product->update([
                        'qty' => $product->qty + $orderItem->qty,
                    ]);
                    // Cập nhật lại số lượng sản phẩm trong bảng receipt_details
                    $productStock = ReceiptDetail::where('product_id', $orderItem->product_id)
                        ->whereColumn('remaining_qty', '<', 'import_qty')
                        ->with('goodsReceipt')
                        ->join('goods_receipts', 'receipt_details.good_receipt_id', '=', 'goods_receipts.id') // Tham gia bảng GoodsReceipt
                        ->orderBy('goods_receipts.import_date', 'desc') // Sắp xếp theo import_date
                        ->select('receipt_details.*') // Chọn các trường từ bảng ReceiptDetail
                        ->get();

                    foreach ($productStock as $stock) {
                        $currentQty = $orderItem->qty;
                        $currentStockQtyImport = $stock->import_qty - $stock->remaining_qty;
                        if ($currentQty > $currentStockQtyImport) {
                            $currentQty = $currentQty - $currentStockQtyImport;
                            $stock->update([
                                'remaining_qty' => $stock->remaining_qty + $currentStockQtyImport,
                            ]);
                        } else {
                            $stock->update([
                                'remaining_qty' => $stock->remaining_qty + $currentQty,
                            ]);
                            break;
                        }
                    }
                }
                DB::commit();
            }
            // Trả về không thể hủy đơn vì đã giao hàng

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
