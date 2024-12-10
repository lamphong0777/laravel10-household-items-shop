<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index(Request $request)
    {
        // get user != admin
        $users = User::where('role', '<>', 2);
        if(filled($request->get('searchText'))) {
            $users = User::where('name', 'like', '%'.$request->get('searchText').'%');
        }

        $users = $users->paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|regex:/(0)[0-9]{9}/',
            'password' => 'required|min:5|max:8',
            'confirm_password' => 'required|same:password'
        ], [
            'name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 5 ký tự',
            'password.max' => 'Mật khẩu không được quá 8 ký tự',
            'confirm_password.required' => 'Vui lòng xác nhận mật khẩu',
            'confirm_password.same' => 'Xác nhận mật khẩu không khớp với mật khẩu'
        ]);

        if ($validator->passes()) {
            // save user
            $password = Hash::make($request->password);
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password,
                'phone' => $request->phone
            ]);

            $request->session()->flash('success', 'Tài khoản được thêm thành công!');
            return response()->json([
                'status' => true,
                'message' => 'User created successfully!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|regex:/(0)[0-9]{9}/',
            'password' => 'nullable|min:5|max:8',
            'confirm_password' => 'same:password'
        ], [
            'name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'password.min' => 'Mật khẩu phải có ít nhất 5 ký tự',
            'password.max' => 'Mật khẩu không được quá 8 ký tự',
            'confirm_password.same' => 'Xác nhận mật khẩu không khớp với mật khẩu'
        ]);

        if ($validator->passes()) {
            // save user
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            if ($request->password != '') {
                $password = Hash::make($request->password);
                $user->password = $password;
            }
            $user->save();
            $request->session()->flash('success', 'Tài khoản khách hàng đã được cập nhật!');
            return response()->json([
                'status' => true,
                'message' => 'User updated successfully!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $user->delete();
            return response()->json([
                'status' => true,
                'message' => 'User deleted successfully!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not found!'
            ]);
        }
    }
}
