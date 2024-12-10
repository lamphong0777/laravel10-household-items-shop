<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Staff;
use App\Models\StaffPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller {
    public function index(Request $request) {
        $staffs = Staff::latest();
        if($request->searchText != '') {
            $staffs = Staff::where('name', 'like', '%'.$request->searchText.'%')->latest();
        }
        $staffs = $staffs->paginate(10);
        return view( 'admin.staff.index', compact('staffs') );
    }

    public function create() {
        $permissions = Permission::all();
        return view( 'admin.staff.create', compact( 'permissions' ) );
    }

    public function store( Request $request ) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:staff',
            'phone' => 'required|min:10|regex:/(0)[0-9]{9}/',
            'address' => 'required',
            'password' => 'required:min:5',
            'confirm_password' => 'required|same:password|min:5',
        ], [
            'name.required' => 'Tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email phải là một địa chỉ email hợp lệ.',
            'email.unique' => 'Email đã được sử dụng vui lòng chọn Email khác.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 chữ số.',
            'phone.regex' => 'Số điện thoại không đúng định dạng, phải bắt đầu bằng số 0 và có 10 chữ số.',
            'address.required' => 'Địa chỉ không được để trống',
            'password.required' => 'Mật khẩu không được để trống.',
            'confirm_password.required' => 'Xác nhận mật khẩu không được để trống.',
            'confirm_password.same' => 'Xác nhận mật khẩu phải giống với mật khẩu.',
        ]);

        if ( $validator->passes() ) {
            // Save staff into the database
            // Save staff account into users table and set role = 2
            $userCreated = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => 2
            ]);
            // Save staff information into staff table name, email, phone, position
            $staffCreated = Staff::create([
                'user_id' => $userCreated->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'position' => $request->position,
            ]);
            // Save permission information into permission_details table staff_id, permission_id ( update use Sync )
            if(!empty($request->permission)) {
                foreach ($request->permission as $permission) {
                    StaffPermission::create([
                        'staff_id' => $staffCreated->id,
                        'permission_id' => $permission
                    ]);
                }
            }
            // return json
            $request->session()->flash( 'success', 'Nhân viên được tạo thành công!' );

            return response()->json( [
                'status' => true,
                'message' => 'Staff created successfully'
            ] );
        }
        return response()->json( [
            'status' => false,
            'errors' => $validator->errors()
        ] );
    }

    public function edit( $id ) {
        $staff = Staff::with('permissions')->find($id);
        $permissions = Permission::all();
        return view( 'admin.staff.edit', compact( 'staff', 'permissions' ) );
    }

    public function update( Request $request, $id ) {
        $dataValidate = [
            'name' => 'required',
            'email' => 'required|email|unique:staff,email,'.$id,
            'phone' => 'required|min:10|regex:/(0)[0-9]{9}/',
            'address' => 'required',
        ];
        if($request->password != '') {
            $dataValidate['password'] = 'required|min:5';
            $dataValidate['confirm_password'] = 'required|min:5|same:password';
        }
        $validator = Validator::make($request->all(), $dataValidate, [
            'name.required' => 'Tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email phải là một địa chỉ email hợp lệ.',
            'email.unique' => 'Email đã được sử dụng vui lòng chọn Email khác.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 chữ số.',
            'phone.regex' => 'Số điện thoại không đúng định dạng, phải bắt đầu bằng số 0 và có 10 chữ số.',
            'address.required' => 'Địa chỉ không được để trống',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải ít nhất 5 ký tự',
            'confirm_password.min' => 'Mật khẩu phải ít nhất 5 ký tự',
            'confirm_password.required' => 'Xác nhận mật khẩu không được để trống.',
            'confirm_password.same' => 'Xác nhận mật khẩu phải giống với mật khẩu.',
        ]);

        if($validator->fails()) {
            return response()->json( [
                'status' => false,
                'errors' => $validator->errors()
            ] );
        }

        $staffInfo = Staff::find($id);
        $staffInfo->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
        ]);

        User::find($staffInfo->user_id)->update([
            'email' => $request->email,
        ]);

        if(!empty($request->password)) {
            User::find($staffInfo->user_id)->update([
                'password' => Hash::make($request->password),
            ]);
        }

        if(!empty($request->permission)) {
            $staffInfo->permissions()->sync($request->permission);
        }

        $request->session()->flash('success', 'Nhân viên được cập nhật thành công!');
        return response()->json( [
            'status' => true,
            'message' => 'Staff updated successfully!'
        ]);
    }

    public function destroy( Request $request, $id ) {
        $staff = Staff::find($id);
        if(empty($staff)) {
            return response()->json([
                'status' => false,
                'message' => 'Staff not found!'
            ]);
        }
        $user = User::find($staff->user_id);
        $user->delete();
        $staff->delete();
        return response()->json([
            'status' => true,
            'message' => 'Staff deleted successfully!'
        ]);
    }

    // Change password
    public function changePassword() {
        return view('admin.staff.change-password');
    }

    public function updatePassword( Request $request ) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|min:5|same:new_password',
        ], [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 5 ký tự',
            'confirm_password.required' => 'Vui lòng xác nhận mật khẩu mới',
            'confirm_password.min' => 'Xác nhận mật khẩu phải có ít nhất 5 ký tự',
            'confirm_password.same' => 'Xác nhận mật khẩu không khớp với mật khẩu mới'
        ]);

        if($validator->fails()) {
            return response()->json( [
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        $uid = Auth::guard('admin')->user()->id;
        $user = User::select('id', 'password')->where('id', $uid)->first();
        if(!Hash::check($request->old_password , $user->password)) {
            $request->session()->flash('error', 'Mật khẩu cũ không đúng vui lòng thử lại.');
            return response()->json( [
                'status' => true,
                'message' => 'Your old password is wrong!'
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
}
