<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $validator = validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email không được trống.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Mật khẩu không được trống.'
        ]);
        if ($validator->passes()) {
            // Kiểm tra đăng nhập với email, password và tùy chọn remember me
            if (Auth::guard('admin')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ], $request->boolean('remember'))) {
                $admin = Auth::guard('admin')->user();

                if ($admin->role == 2) {
                    // Nếu quyền hợp lệ, chuyển hướng đến trang dashboard
                    return redirect()->route('admin.dashboard');
                } else {
                    // Đăng xuất và thông báo lỗi nếu quyền không hợp lệ
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')
                        ->with('error', 'Bạn không có quyền đăng nhập vào hệ thống.');
                }
            } else {
                // Thông báo lỗi nếu thông tin đăng nhập không chính xác
                return redirect()->route('admin.login')
                    ->with('error', 'Email hoặc mật khẩu không đúng.');
            }
        } else {
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
}
