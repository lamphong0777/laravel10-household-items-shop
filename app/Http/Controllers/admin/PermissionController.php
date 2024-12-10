<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller {
    public function index(Request $request) {
        $permissions = Permission::latest();
        if($request->get('searchText')) {
            $permissions->where('name', 'like', '%'.$request->get('searchText').'%');
        }
        $permissions = $permissions->paginate(10);

        return view( 'admin.permission.index', compact( 'permissions' ) );
    }

    public function store( Request $request ) {
        $validator = Validator::make($request->all(), [
            'permission_name' => 'required',
            'slug' => 'required|unique:permissions,slug',
        ], [
            'permission_name.required' => 'Tên quyền không được trống.',
            'slug.required' => 'Slug không được trống.',
            'slug.unique' => 'Slug đã tồn tại, vui lòng chọn slug khác.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        }
        // Save permission into the database
        Permission::create([
            'name' => $request->permission_name,
            'slug' => $request->slug,
        ]);
        $request->session()->flash('success', 'Đã thêm quyền thành công');
        return response()->json([
            'status' => true,
            'message' => 'Permission added successfully',
        ]);
    }

    public function edit($id) {
        $permission = Permission::find($id);
        return view('admin.permission.edit', compact('permission'));
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'permission_name' => 'required',
            'slug' => 'required|unique:permissions,slug,'.$id,
        ], [
            'permission_name.required' => 'Tên quyền không được trống.',
            'slug.required' => 'Slug không được trống.',
            'slug.unique' => 'Slug đã tồn tại, vui lòng chọn slug khác.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        }

        Permission::find($id)->update([
            'name' => $request->get('permission_name'),
            'slug' => $request->get('slug'),
        ]);

        $request->session()->flash('success', 'Quyền sửa thành công!');
        return response()->json([
            'status' => true,
            'message' => 'Permission updated successfully',
        ]);
    }
    public function destroy(Request $request, $id) {
        $permission = Permission::find($id);
        if(!$permission) {
            return response()->json([
                'status' => false,
                'message' => 'Permission not found',
            ]);
        }
        $permission->delete();

        $request->session()->flash('success', 'Permission deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Permission deleted successfully',
        ]);
    }
}
