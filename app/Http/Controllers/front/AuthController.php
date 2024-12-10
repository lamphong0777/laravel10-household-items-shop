<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordEmail;
use App\Models\CustomerAddress;
use App\Models\District;
use App\Models\Province;
use App\Models\User;
use App\Models\Ward;
use App\Models\Wishlist;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function index()
    {
        return view('front.pages.auth.login-register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|min:10|max:10',
            'password' => 'required|min:5',
            'passwordConfirm' => 'required|same:password',
        ], [
            'name.required' => 'Tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 chữ số.',
            'phone.max' => 'Số điện thoại không được vượt quá 10 chữ số.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 5 ký tự.',
            'passwordConfirm.required' => 'Xác nhận mật khẩu không được để trống.',
            'passwordConfirm.same' => 'Xác nhận mật khẩu không khớp với mật khẩu.',
        ]);

        if ($validator->passes()) {
            // save user information into database
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            // return response
            return response()->json([
                'status' => true,
                'message' => 'Registration Successful',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_email' => 'required|email',
            'login_password' => 'required|min:5',
        ], [
            'login_email.required' => 'Email không được để trống.',
            'login_email.email' => 'Email không đúng định dạng.',
            'login_password.required' => 'Mật khẩu không được để trống.',
            'login_password.min' => 'Mật khẩu phải có ít nhất 5 ký tự.',
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->input('login_email'), 'password' => $request->input('login_password')])) {

                if (session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }

                return redirect()->route('home');
            } else {
                session()->flash('error', 'Tài khoản hoặc mật khẩu không đúng.');
                return redirect()->route('shop.account')->withInput($request->only('login_email'));
            }
        } else {
            return redirect()->route('shop.account')
                ->withErrors($validator)
                ->withInput($request->only('login_email'));
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('shop.account');
    }

    public function profile($id)
    {
        $user = User::find($id);
        $customer_order_info = CustomerAddress::where('user_id', $id)->first();
        $provinces = Province::all();
        if ($customer_order_info) {
            $districts = District::where('province_id', $customer_order_info->province_id)->get();
            $wards = Ward::where('district_id', $customer_order_info->district_id)->get();
        } else {
            $districts = null;
            $wards = null;
        }
        return view('front.pages.auth.profile', compact(
            'user',
            'customer_order_info',
            'provinces',
            'districts',
            'wards'
        ));
    }

    public function wishlist()
    {
        $uid = Auth::id();
        $wishlists = Wishlist::where('user_id', $uid)->get();
        return view('front.pages.auth.wishlist', compact('wishlists'));
    }

    public function updateProductWishlist(Request $request)
    {
        if (Auth::check()) {
            // save user wish list
            $uid = Auth::user()->id;
            $product_id = $request->product_id;
            $wishlistExist = Wishlist::where(['user_id' => $uid, 'product_id' => $product_id])->first();
            if ($wishlistExist) {
                // wish list exit -> remove
                $wishlistExist->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Đã xóa sản phẩm khỏi yêu thích',
                    'icon' => 'remove'
                ]);
            } else {
                // wish list is not exit -> add
                Wishlist::create([
                    'user_id' => $uid,
                    'product_id' => $product_id
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Đã thêm sản phẩm vào yêu thích',
                    'icon' => 'add'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Bạn cần đăng nhập',
                'icon' => '',
            ]);
        }
    }

    public function updateCustomerProfile(Request $request)
    {
        $uid = Auth::id();
        $validator = Validator::make($request->all(), [
            'account_name' => 'required',
            'account_phone' => 'required|min:10|max:10',
            'account_email' => 'required|email|unique:users,email,' . $uid,
        ]);

        if ($validator->passes()) {
            // update
            User::find($uid)->update([
                'name' => $request->input('account_name'),
                'phone' => $request->input('account_phone'),
                'email' => $request->input('account_email'),
            ]);
            $request->session()->flash('success', 'Cập nhật thành công!');
            return response()->json(['status' => true, 'message' => 'Update successfully']);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateCustomerAddress(Request $request)
    {
        $uid = Auth::id();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|min:10|max:10',
            'address' => 'required',
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'email' => 'required|email',
        ], [
            'name.required' => 'Tên không được trống',
            'phone.required' => 'Số điện thoại không được trống',
            'phone.min' => 'Số điện thoại phải đủ 10 chữ số',
            'phone.max' => 'Số điện thoại phải đủ 10 chữ số',
            'address.required' => 'Địa chỉ không được trống',
            'province.required' => 'Vui lòng chọn tỉnh/thành phố',
            'district.required' => 'Vui lòng chọn quận/huyện',
            'ward.required' => 'Vui lòng chọn phường/xã',
            'email.required' => 'Email không được trống',
            'email.email' => 'Email không hợp lệ',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        CustomerAddress::updateOrCreate(
            [
                'user_id' => $uid,
            ],
            [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'province_id' => $request->input('province'),
                'district_id' => $request->input('district'),
                'ward_id' => $request->input('ward'),
            ]
        );
        $request->session()->flash('success', 'Cập nhật thành công!');
        return response()->json([
            'status' => true,
            'message' => 'Update successfully',
        ]);
    }

    public function changePassword()
    {
        return view('front.pages.auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $messages = [
            'old_password.required' => 'Mật khẩu cũ là bắt buộc.',
            'new_password.required' => 'Mật khẩu mới là bắt buộc.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 5 ký tự.',
            'confirm_password.required' => 'Xác nhận mật khẩu là bắt buộc.',
            'confirm_password.same' => 'Xác nhận mật khẩu phải giống với mật khẩu mới.',
        ];
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        $uid = Auth::id();
        $user = User::select('id', 'password')->where('id', $uid)->first();
        if (!Hash::check($request->old_password, $user->password)) {
            $request->session()->flash('error', 'Mật khẩu cũ không đúng vui lòng thử lại');

            return response()->json([
                'status' => true,
                'message' => 'Your old password is wrong'
            ]);
        }

        User::find($uid)->update([
            'password' => Hash::make($request->new_password),
        ]);
        $request->session()->flash('success', 'Đã đổi mật khẩu thành công!');
        return response()->json([
            'status' => true,
            'message' => 'Update new password successfully'
        ]);
    }

    public function forgotPassword()
    {
        return view('front.pages.auth.forgot-password');
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Trường email là bắt buộc.',
            'email.email' => 'Trường email phải là một địa chỉ email hợp lệ.',
            'email.exists' => 'Địa chỉ email không tồn tại trong hệ thống.',
        ]);
        if ($validator->fails()) {
            return redirect()->route('user.forgot-password')->withInput()->withErrors($validator);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // send email to reset password
        $user = User::where('email', $request->email)->first();
        $formData = [
            'token' => $token,
            'user' => $user,
            'subject' => 'You have request to reset your password',
        ];
        Mail::to($request->email)->send(new ResetPasswordEmail($formData));

        return redirect()->route('user.forgot-password')->with('success', 'Đường dẫn khôi phục mật khẩu đã được gửi đến email của bạn.');
    }

    public function resetPasswordToken($token)
    {
        $tokenExist = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$tokenExist) {
            return redirect()->route('user.forgot-password')->with('error', 'Token không đúng hoặc đã hết hạn.');
        }
        return view('front.pages.auth.reset-password', compact('token'));
    }

    public function updatePasswordToken(Request $request)
    {
        $token = $request->token;
        $tokenObj = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$tokenObj) {
            return redirect()->route('user.forgot-password')->with('error', 'Token không đúng hoặc đã hết hạn.');
        }

        $user = User::where('email', $tokenObj->email)->first();
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ], [
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 5 ký tự.',
            'confirm_password.required' => 'Vui lòng xác nhận mật khẩu.',
            'confirm_password.same' => 'Mật khẩu xác nhận không khớp với mật khẩu mới.',
        ]);
        if ($validator->fails()) {
            return redirect()->route('user.email-reset-password', $token)->withInput()->withErrors($validator);
        }

        User::find($user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        DB::table('password_reset_tokens')->where('token', $token)->delete();
        return redirect()->route('shop.account')->with('success', 'Đã cập nhật mật khẩu thành công.');
    }
}
