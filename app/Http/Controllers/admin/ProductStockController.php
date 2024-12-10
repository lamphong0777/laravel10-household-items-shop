<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceipt;
use App\Models\Producer;
use App\Models\Product;
use App\Models\ReceiptDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductStockController extends Controller
{
    // ---------------------Goods receipt------------------------- //
    public function indexReceipt(Request $request) {
        $goodsReceipts = GoodsReceipt::paginate(10);

        return view('admin.product_stock.index-receipt', compact('goodsReceipts'));
    }

    public function createReceipt(Request $request) {
        $producers = Producer::all();
        $products = Product::all();
        return view('admin.product_stock.create-receipt', compact('producers', 'products'));
    }

    public function storeReceipt(Request $request) {
//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'producer_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        $staffId = $user->staff->id;
        DB::beginTransaction();

        try {
            // Tạo một GoodsReceipt
            $goodsReceipt = GoodsReceipt::create([
                'producer_id' => $request->producer_id,
                'staff_id' => $staffId,
                'import_date' => $request->import_date,
                'total_price' => 0,
                'notes' => $request->notes,
            ]);

            $totalPrice = 0;

            // Lặp qua danh sách sản phẩm và thêm vào ReceiptDetail
            foreach ($request->products as $product) {
                $subtotal = $product['import_qty'] * $product['import_price'];
                $totalPrice += $subtotal;

                ReceiptDetail::create([
                    'product_id' => $product['product_id'],
                    'good_receipt_id' => $goodsReceipt->id,
                    'import_qty' => $product['import_qty'],
                    'remaining_qty' => $product['import_qty'],
                    'import_price' => $product['import_price'],
                ]);

                // Cập nhật số lượng Product
                $productModel = Product::find($product['product_id']);
                if ($productModel) {
                    $productModel->update([
                        'qty' => $productModel->qty + $product['import_qty'],
                    ]);
                }
            }

            // Cập nhật total_price cho GoodsReceipt
            GoodsReceipt::find($goodsReceipt->id)->update([
                'total_price' => $totalPrice,
            ]);

            DB::commit();
            $request->session()->flash('success', 'Đã thêm phiếu nhập hàng thành công.'. $totalPrice);
            return response()->json([
                'status' => true,
                'message' => 'Goods receipt created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create goods receipt', 'details' => $e->getMessage()]);
        }
    }

    public function showReceipt($id) {
        $receipt = GoodsReceipt::find($id);
        return view('admin.product_stock.show-receipt', compact('receipt'));
    }

    public function editReceipt($id)
    {

    }

    public function updateReceipt(Request $request, $id)
    {

    }

    public function destroyReceipt($id)
    {
        $receipt = GoodsReceipt::find($id);

        if(empty($receipt)) {
            return response()->json([
                'status' => false,
                'message' => 'Receipt not found!',
            ]);
        }

        $receipt->delete();
        session()->flash('success', 'Đã xóa thành công.');
        return response()->json([
            'status' => true,
            'message' => 'Receipt deleted successfully.',
        ]);
    }
}
