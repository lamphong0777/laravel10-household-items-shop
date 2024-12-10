<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ReceiptDetail;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function processCheckout(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255|min:3',
                'email' => 'required|string|email|max:255',
                'province' => 'required',
                'district' => 'required',
                'ward' => 'required',
                'phone' => 'required|regex:/(0)[0-9]{9}/',
            ],
            [
                'name.required' => 'Tên không được để trống',
                'name.min' => 'Tên không được ít hơn 3 ký tự',
                'name.max' => 'Tên không được vượt quá 255 ký tự',
                'email.required' => 'Email không được để trống',
                'email.email' => 'Email không đúng định dạng',
                'email.max' => 'Email không được vượt quá 255 ký tự',
                'province.required' => 'Tỉnh/Thành phố không được để trống',
                'district.required' => 'Quận/Huyện không được để trống',
                'ward.required' => 'Phường/Xã không được để trống',
                'phone.required' => 'Số điện thoại không được để trống',
                'phone.regex' => 'Số điện thoại không đúng định dạng (phải là 10 chữ số bắt đầu bằng 0)',
            ]
        );

        if ($validator->passes()) {
            // Lấy thông tin khách hàng
            $user = Auth::user();
            // Lấy tổng tiền sản phẩm trong giỏ hàng
            $total = app(CartController::class)->total($user->id);
            // Lấy phí vận chuyển
            $shipping_charge = ShippingCharge::where('province_id', $request->province)->first();
            $shipping = $shipping_charge->shipping_cost;
            // Tính tổng tiền chưa gồm giảm giá
            $grand_total = $total + $shipping;
            // Lấy mã giảm giá
            $couponCode = '';
            $discountAmount = 0;
            if (!empty($request->discount_code)) {
                $discount = DiscountCoupon::where('code', $request->discount_code)->first();

                if ($discount) {
                    $couponCode = $discount->code;
                    $discountValue = $discount->discount_value;

                    // Tính toán giá trị giảm giá dựa trên loại
                    $discountAmount = $discount->type === 'percent'
                        ? $grand_total * ($discountValue / 100)
                        : $discountValue;

                    // Cập nhật tổng giá trị sau khi giảm giá
                    $grand_total -= $discountAmount;
                }
            }
            // Kiểm tra số lượng sản phẩm trong kho còn đủ ?
            if ($this->checkProductQty($user->id)) {
                $request->session()->flash('error', 'Số lượng sản phẩm trong kho không đủ, vui lòng thay đổi số lượng!');
                return redirect()->route('shopping.cart');
            }
            // Xử lý đặt hàng
            if ($request->payment_method == 'cod') {
                try {
                    // Lưu hóa đơn vào cơ sở dữ liệu
                    $orderId = $this->saveOrder($request, $total, $shipping, $grand_total, $couponCode, $discountAmount);
                    // Lưu chi tiết hóa đơn
                    $this->saveOrderItems($user->id, $orderId);
                    // Cập nhật địa chỉ khách hàng
                    $this->saveCustomerAddress($request, $user->id);
                    // Đặt hàng thành công
                    // Cập nhật kho
                    $this->updateProductInStock($orderId);
                    // Xóa giỏ hàng của khách hàng khi đã đặt hàng thành công
                    app(CartController::class)->destroyCart($user->id);
                    $request->session()->flash('success', 'Đơn hàng sẽ sớm được xử lý và giao đến bạn!');
                    // Chuyển hướng đến giỏ hàng và thông báo thành công
                    return redirect()->route('shopping.cart');
                } catch (\Exception $exception) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => $exception->getMessage()
                    ]);
                }
            } else if ($request->payment_method == 'vn_pay') {
                // Thanh toán qua VN PAY
                // Lưu hóa đơn vào cơ sở dữ liệu
                $orderId = $this->saveOrder($request, $total, $shipping, $grand_total, $couponCode, $discountAmount);
                // Lưu chi tiết hóa đơn
                $this->saveOrderItems($user->id, $orderId);
                // Cập nhật địa chỉ khách hàng
                $this->saveCustomerAddress($request, $user->id);
                // vnpay payment
                return $this->createPayment($request, $orderId, $grand_total);
            } else if ($request->payment_method == 'momo') {
                // Thanh toán qua ATM MOMO
                // Lưu hóa đơn vào cơ sở dữ liệu
                $orderId = $this->saveOrder($request, $total, $shipping, $grand_total, $couponCode, $discountAmount);
                // Lưu chi tiết hóa đơn
                $this->saveOrderItems($user->id, $orderId);
                // Cập nhật địa chỉ khách hàng
                $this->saveCustomerAddress($request, $user->id);
                return $this->momoPayment($request, $orderId, $grand_total);
            }
        }
        // return redirect to check out page and show error validate
        return redirect()->route('shop.checkout')
            ->withErrors($validator)
            ->withInput($request->all());
    }

    private function saveCustomerAddress(Request $request, $uid): void
    {
        try {
            DB::beginTransaction();
            // Sử dụng firstOrNew để tìm hoặc tạo bản ghi mới
            $customerAddress = CustomerAddress::firstOrNew(['user_id' => $uid]);
            // Gán giá trị mới vào các trường
            $customerAddress->fill([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'province_id' => $request->province,
                'district_id' => $request->district,
                'ward_id' => $request->ward,
                'address' => $request->address,
            ]);
            // Lưu lại bản ghi
            $customerAddress->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            // Ghi log ngoại lệ để dễ dàng theo dõi
            \Log::error('Error saving customer address: ' . $exception->getMessage());
        }
    }

    public function saveOrder(Request $request, $total, $shipping, $grand_total, $couponCode, $discountShow)
    {
        try {
            $user = Auth::user();
            DB::beginTransaction();
            // save data to orders table
            $order = new Order();
            $order->id = time() . $user->id;
            $order->user_id = $user->id;
            $order->subtotal = $total;
            $order->shipping = $shipping;
            // save coupon code
            $order->coupon_code = $couponCode;
            // save discount value
            $order->discount = $discountShow;
            $order->grand_total = $grand_total;
            $order->order_status = 'pending';
            $order->payment_method = $request->payment_method;
            /// customer address
            $order->name = $request->name;
            $order->email = $request->email;
            $order->phone = $request->phone;
            $order->province_id = $request->province;
            $order->district_id = $request->district;
            $order->ward_id = $request->ward;
            $order->address = $request->address;
            $order->notes = $request->order_notes;
            $order->save();
            DB::commit();

            return $order->id;
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }

    public function saveOrderItems($uid, $orderId): void
    {
        try {
            // Lấy danh sách sản phẩm trong giỏ hàng
            $cartContent = Cart::where('user_id', $uid)->get();

            DB::beginTransaction();

            foreach ($cartContent as $cartItem) {
                // Lưu thông tin sản phẩm vào bảng order_items
                OrderItem::create([
                    'order_id' => $orderId,
                    'product_id' => $cartItem->product_id,
                    'title' => $cartItem->title,
                    'qty' => $cartItem->qty,
                    'price' => $cartItem->price,
                    'total' => $cartItem->qty * $cartItem->price,
                ]);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::error('Error saving order items: ' . $exception->getMessage());
            throw $exception;
        }
    }

    public function updateProductInStock($orderId)
    {
        try {
            // Lấy danh sách sản phẩm trong chi tiết hóa đơn
            $products = OrderItem::where('order_id', $orderId)->get();
            // Xử lý cập nhật kho
            DB::beginTransaction();
            foreach ($products as $productItem) {
                // Cập nhật tồn kho trong bảng product_stock
                $productStock = ReceiptDetail::where('product_id', $productItem->product_id)
                    ->where('remaining_qty', '>', 0)
                    ->with('goodsReceipt')
                    ->join('goods_receipts', 'receipt_details.good_receipt_id', '=', 'goods_receipts.id') // Tham gia bảng GoodsReceipt
                    ->orderBy('goods_receipts.import_date', 'asc') // Sắp xếp theo import_date
                    ->select('receipt_details.*') // Chọn các trường từ bảng ReceiptDetail
                    ->get();

                $amountRemain = $productItem->qty;

                foreach ($productStock as $productStockItem) {
                    if ($amountRemain > $productStockItem->remaining_qty) {
                        $amountRemain -= $productStockItem->remaining_qty;
                        $productStockItem->update(['remaining_qty' => 0]);
                    } else {
                        $productStockItem->update(['remaining_qty' => $productStockItem->remaining_qty - $amountRemain]);
                        break;
                    }
                }
                // Cập nhật số lượng sản phẩm trong bảng products
                $product = Product::find($productItem->product_id);
                if ($product) {
                    $product->update([
                        'qty' => $product->qty - $productItem->qty,
                    ]);
                }
            }
            DB::commit();
            // Gửi email xác nhận đơn hàng
            orderEmail($orderId, 'customer');
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::error('Error saving order items: ' . $exception->getMessage());
            throw $exception;
        }
    }

    public function momoPayment(Request $request, $order, $grand_total)
    {
        $endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = 'Thanh toan qua ATM MoMo';
        $amount = $grand_total;
        $orderId = $order;
        $redirectUrl = 'http://127.0.0.1:8000/account/momo-payment';
        // đường dẫn muốn trả về
        $notifyUrl = 'http://127.0.0.1:8000/account/momo-payment-post';
        $ipnUrl = 'http://127.0.0.1:8000/account/checkout';
        // đường dẫn truyền vào
        $extraData = '';

        if (!empty($_POST)) {
            //            $requestId = time() . '';
            $requestType = 'payWithATM';
            // $extraData = ( $_POST[ 'extraData' ] ? $_POST[ 'extraData' ] : '' );
            //before sign HMAC SHA256 signature
            $rawHash = 'accessKey=' . $accessKey . '&amount=' . $amount . '&extraData=' . $extraData . '&ipnUrl=' . $ipnUrl . '&orderId=' . $orderId . '&orderInfo=' . $orderInfo . '&partnerCode=' . $partnerCode . '&redirectUrl=' . $redirectUrl . '&requestId=' . $orderId . '&requestType=' . $requestType;

            //            $rawHash = 'accessKey=klm05TvNBzhg7h7j';
            //            $rawHash .= '&amount=' . $amount;
            //            $rawHash .= '&extraData=';
            //            $rawHash .= '&ipnUrl=' . $ipnUrl;
            //            $rawHash .= '&orderId=' . $orderId;
            //            $rawHash .= '&orderInfo=Thanh toan online';
            //            $rawHash .= '&partnerCode=MOMOBKUN20180529';
            //            $rawHash .= '&redirectUrl=' . $redirectUrl;
            //            $rawHash .= '&requestId=' . $orderId;
            //            $rawHash .= '&requestType=payWithATM';

            $signature = hash_hmac('sha256', $rawHash, $secretKey);
            $data = array(
                'partnerCode' => $partnerCode,
                'partnerName' => 'Test',
                'storeId' => 'MomoTestStore',
                'requestId' => $orderId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'notifyUrl' => $notifyUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            );
            $result = $this->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);
            // decode json

            //Just an example, please check more in there

            // header( 'Location: ' . $jsonResult[ 'payUrl' ] );
            return redirect()->to($jsonResult['payUrl']);
        }
    }

    public function handleMomoPayment(Request $request)
    {
        if (!empty($_GET)) {
            $partnerCode = $_GET['partnerCode'];
            $orderId = $_GET['orderId'];
            // $localMessage = utf8_encode( $_GET[ 'localMessage' ] );
            $message = $_GET['message'];
            $transId = $_GET['transId'];
            $orderInfo = utf8_encode($_GET['orderInfo']);
            $amount = $_GET['amount'];
            // $errorCode = $_GET[ 'errorCode' ];
            $resultCode = $_GET['resultCode'];
            $responseTime = $_GET['responseTime'];
            $requestId = $_GET['requestId'];
            $extraData = $_GET['extraData'];
            $payType = $_GET['payType'];
            $orderType = $_GET['orderType'];

            $m2signature = $_GET['signature'];
            // MoMo signature

            $rawHash = 'accessKey=klm05TvNBzhg7h7j';
            $rawHash .= '&amount=' . $amount;
            $rawHash .= '&extraData=' . $extraData;
            $rawHash .= '&message=' . $message;
            $rawHash .= '&orderId=' . $orderId;
            $rawHash .= '&orderInfo=' . $orderInfo;
            $rawHash .= '&orderType=' . $orderType;
            $rawHash .= '&partnerCode=' . $partnerCode;
            $rawHash .= '&payType=' . $payType;
            $rawHash .= '&requestId=' . $requestId;
            $rawHash .= '&responseTime=' . $responseTime;
            $rawHash .= '&resultCode=' . $resultCode;
            $rawHash .= '&transId=' . $transId;
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

            //Checksum
            //            $rawHash = 'partnerCode=' . $partnerCode . '&accessKey=' . $accessKey . '&requestId=' . $requestId . '&amount=' . $amount . '&orderId=' . $orderId . '&orderInfo=' . $orderInfo . '&orderType=' . $orderType . '&transId=' . $transId . '&message=' . $message . '&responseTime=' . $responseTime . '&resultCode=' . $resultCode . '&payType=' . $payType . '&extraData=' . $extraData;

            $partnerSignature = hash_hmac('sha256', $rawHash, $secretKey);

            //            echo "<script>console.log('Debug huhu Objects: " . $rawHash . "' );</script>";
            //            echo "<script>console.log('Debug huhu Objects: " . $secretKey . "' );</script>";
            //            echo "<script>console.log('Debug huhu Objects: " . $partnerSignature . "' );</script>";

            if ($partnerSignature == $m2signature) {
                if ($resultCode == '0') {
                    // Thanh toán thành công
                    // Cập nhật trạng thái hóa đơn thành đã thanh toán qua MOMO
                    // Tìm hóa đơn và cập nhật trạng thái
                    Order::find($request->orderId)->update([
                        'paid_date' => now(),
                        'transaction_id' => $request->transId
                    ]);
                    // Cập nhật kho
                    $this->updateProductInStock($request->orderId);
                    // Xóa giỏ hàng của khách hàng khi đã đặt hàng thành công
                    app(CartController::class)->destroyCart(Auth::id());
                    // Chuyển hướng thanh toán thành công
                    $request->session()->flash('success', 'Đặt hàng thành công!');
                    return redirect()->route('shopping.cart');
                } else {
                    // Cập nhật trạng thái hóa đơn thanh toán thất bại
                    // Tìm hóa đơn và cập nhật trạng thái
                    Order::find($request->orderId)->update([
                        'order_status' => 'failed'
                    ]);
                    // redirect to cart page and show alert order cancelled
                    $request->session()->flash('order_cancelled', 'Đơn hàng đã được hủy!');
                    return redirect()->route('shopping.cart');
                }
            } else {
                $request->session()->flash('error', 'Lỗi xác thực!');
                $orderId = $request->orderId;
                Order::find($orderId)->delete();
                // delete order temp
                return redirect()->route('shopping.cart');
            }
        }
    }

    // vn pay ment

    public function createPayment(Request $request, $orderId, $grand_total)
    {
        $vnp_TmnCode = env('VNP_TMN_CODE');
        // Mã website
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        // Chuỗi bí mật
        $vnp_Url = env('VNP_URL');
        // URL thanh toán
        $vnp_Returnurl = env('VNP_RETURN_URL');
        // URL callback
        $vnp_TxnRef = $orderId;
        // Mã giao dịch. Bạn có thể tạo ra số random
        $vnp_OrderInfo = 'Thanh toán đơn hàng';
        // Thông tin đơn hàng
        $vnp_OrderType = 'billpayment';
        // Loại đơn hàng
        $vnp_Amount = $grand_total * 100;
        // Số tiền thanh toán ( x100 vì VNPay dùng đơn vị VNĐ )
        $vnp_Locale = 'vn';
        // Ngôn ngữ
        $vnp_BankCode = '';
        // Mã ngân hàng ( có thể để trống )
        $vnp_IpAddr = $request->ip();
        // Địa chỉ IP của khách hàng
        $inputData = array(
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $vnp_TmnCode,
            'vnp_Amount' => $vnp_Amount,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $vnp_IpAddr,
            'vnp_Locale' => $vnp_Locale,
            'vnp_OrderInfo' => $vnp_OrderInfo,
            'vnp_OrderType' => $vnp_OrderType,
            'vnp_ReturnUrl' => $vnp_Returnurl,
            'vnp_TxnRef' => $vnp_TxnRef,
        );
        // Tạo querystring từ mảng dữ liệu
        ksort($inputData);
        $query = '';
        $i = 0;
        $hashdata = '';
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . '=' .
                    urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' .
                    urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . '=' . urlencode($value) .
                '&';
        }
        $vnp_Url = $vnp_Url . '?' . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac(
                'sha512',
                $hashdata,
                $vnp_HashSecret
            );
            // Tạo mã HMAC SHA512
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        return redirect($vnp_Url);
        // Chuyển hướng người dùng tới VNPay
    }

    public function returnPayment(Request $request)
    {
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == 'vnp_') {
                $inputData[$key] = $value;
            }
        }
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = '';
        foreach ($inputData as $key => $value) {
            $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
        }
        $hashData = trim($hashData, '&');

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            if ($inputData['vnp_ResponseCode'] == '00') {
                // Thanh toán thành công
                // Cập nhật trạng thái hóa đơn thành đã thanh toán qua VN Pay
                // Tìm hóa đơn và cập nhật trạng thái
                Order::find($inputData['vnp_TxnRef'])->update([
                    'paid_date' => $inputData['vnp_PayDate'],
                    'transaction_id' => $request->vnp_TransactionNo
                ]);
                // Cập nhật kho
                $this->updateProductInStock($inputData['vnp_TxnRef']);
                // Xóa giỏ hàng của khách hàng khi đã đặt hàng thành công
                app(CartController::class)->destroyCart(Auth::id());
                // Chuyển hướng thanh toán thành công
                $request->session()->flash('success', 'Đặt hàng thành công!');
                return redirect()->route('shopping.cart');
            } else {
                // Cập nhật trạng thái hóa đơn thanh toán thất bại
                // Tìm hóa đơn và cập nhật trạng thái
                Order::find($inputData['vnp_TxnRef'])->update([
                    'order_status' => 'failed'
                ]);
                // redirect to cart page and show alert order cancelled
                $request->session()->flash('order_cancelled', 'Đơn hàng đã được hủy!');
                return redirect()->route('shopping.cart');
            }
        } else {
            return response()->json(['error' => 'Invalid signature'], 400);
        }
    }


    public function checkProductQty($uid): int
    {
        $cartContent = Cart::where('user_id', $uid)->get();
        foreach ($cartContent as $cartItem) {
            $productQtyCheck = Product::find($cartItem->product_id);
            if ($productQtyCheck->qty < $cartItem->qty) {
                return 1;
            }
        }
        return 0;
    }

    function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function cancelOrder(Request $request, $id)
    {
        try {
            $order = Order::find($id);
            if ($order->order_status == 'pending') {
                if($order->payment_method == 'momo') {
//                    $responseData = $this->refundMoMo($order->id, $order->transaction_id, $order->grand_total, "Hoàn tiền");
//                    if(!$responseData['success']) {
//                        return response()->json([
//                            'status' => false,
//                            'message' => $responseData['message']
//                        ]);
//                    }
                    return response()->json([
                        'status' => false,
                        'message' => 'Không thể hủy đơn thanh toán MOMO'
                    ]);
                }
                if($order->payment_method == 'vn_pay') {
//                    $responseData = $this->refundVnPay($order->id, $order->transaction_id, $order->grand_total);
//                    if($responseData['vnp_ResponseCode'] != "00") {
//                        return response()->json([
//                            'status' => false,
//                            'message' => $responseData['vnp_Message']
//                        ]);
//                    }
                    return response()->json([
                        'status' => false,
                        'message' => 'Không thể hủy đơn thanh toán vnPay'
                    ]);
                }
                DB::beginTransaction();
                $order->update([
                     'order_status' => 'cancelled'
                ]);
                // Lấy danh sách sản phẩm
                $orderItems = OrderItem::where('order_id', $id)->get();
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
                $request->session()->flash('success', 'Đã hủy đơn thành công.');
                return response()->json([
                    'status' => true,
                    'message' => "Order cancelled successfully."
                ]);
            }
            // Trả về không thể hủy đơn vì đã giao hàng
//            $request->session()->flash('error', 'Không thể hủy đơn hàng này.');
            return response()->json([
                'status' => false,
                'message' => "Order cancel failed."
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function refundMoMo($orderId, $transId, $amount, $description)
    {
        // Lấy thông tin từ file .env
        $partnerCode = "MOMOBKUN20180529";
        $accessKey = "klm05TvNBzhg7h7j";
        $secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";
        $refundUrl = "https://test-payment.momo.vn/gw_payment/transactionProcessor";
        // Tạo mã yêu cầu hoàn tiền duy nhất
        $requestId = uniqid($orderId . '_refund_');
        // Thời gian gửi yêu cầu
        $requestTime = time() * 1000;
        // Dữ liệu cần gửi đến API
        $data = [
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'transId' => $transId,
            'description' => $description,
            'requestType' => 'refundMoMoWallet',
            'lang' => 'vi',
        ];
        // Tạo chữ ký (signature)
        $rawHash = "partnerCode={$data['partnerCode']}&accessKey={$data['accessKey']}&requestId={$data['requestId']}&amount={$data['amount']}&orderId={$data['orderId']}&transId={$data['transId']}&description={$data['description']}&requestType={$data['requestType']}";
        $signature = hash_hmac('sha256', $rawHash, $secretKey);
        // Thêm chữ ký vào dữ liệu
        $data['signature'] = $signature;
        // Gửi yêu cầu hoàn tiền qua HTTP
        $response = Http::post($refundUrl, $data);
        // Xử lý phản hồi
        if ($response->successful()) {
            $responseData = $response->json();
            if ($responseData['resultCode'] == 0) {
                // Hoàn tiền thành công
                return [
                    'success' => true,
                    'message' => $responseData['message'],
                    'data' => $responseData,
                ];
            }
            // Hoàn tiền thất bại
            return [
                'success' => false,
                'message' => $responseData['message'],
                'data' => $responseData,
            ];
        }
        // Lỗi kết nối hoặc phản hồi không hợp lệ
        return [
            'success' => false,
            'message' => 'Unable to process refund request.',
        ];
    }

    function refundVNPay($orderId, $transactionNo, $amount)
    {
        $order = Order::find($orderId);
        // Sửa
        $formattedDate = date('YmdHis', strtotime($order->paid_date));

        $vnp_TmnCode = env('VNP_TMN_CODE');
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $vnp_ApiUrl  = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";

        // Tạo requestId và thời gian gửi yêu cầu
        $requestId = uniqid('refund_');
        $createDate = date('YmdHis');

        // Thông tin gửi yêu cầu
        $vnp_Params = [
            'vnp_Command' => 'refund',
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $vnp_TmnCode,
            'vnp_TransactionNo' => $transactionNo,
            'vnp_TxnRef' => $orderId,
            'vnp_Amount' => $amount * 100, // VNPAY yêu cầu số tiền theo đơn vị nhỏ nhất
            'vnp_TransactionType' => "02", // 02: Hoàn tiền toàn phần, 03: Hoàn tiền một phần
            'vnp_OrderInfo' => "Hoan tien vn pay",
            'vnp_CreateDate' => $createDate,
            'vnp_RequestId' => $requestId,
            'vnp_IpAddr' => request()->ip(),
        ];

        // Tạo chuỗi hash
        ksort($vnp_Params);
        $hashdata = "";
        foreach ($vnp_Params as $key => $value) {
            $hashdata .= $key . "=" . $value . "&";
        }
        $hashdata = rtrim($hashdata, "&");
        $vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        // Gắn chữ ký vào tham số
        $vnp_Params['vnp_SecureHash'] = $vnp_SecureHash;

        // Gửi yêu cầu đến API VNPAY
        $response = Http::post($vnp_ApiUrl, $vnp_Params);

        // Trả về phản hồi
        if ($response->successful()) {
            return $response->json();
        }

        return [
            'status' => false,
            'message' => 'Unable to connect to VNPAY',
            'error' => $response->body(),
        ];

        // {
        //     "vnp_ResponseCode": "00",
        //     "vnp_Message": "Giao dịch thành công",
        //     "vnp_TmnCode": "2QXUI4J4",
        //     "vnp_TxnRef": "123456789",
        //     "vnp_Amount": 1000000,
        //     "vnp_TransactionNo": "1234567890",
        //     "vnp_TransactionType": "02",
        //     "vnp_TransactionStatus": "00",
        //     "vnp_OrderInfo": "Hoàn tiền cho đơn hàng 123456789",
        //     "vnp_PayDate": "20231124153000"
        // }
    }
}
