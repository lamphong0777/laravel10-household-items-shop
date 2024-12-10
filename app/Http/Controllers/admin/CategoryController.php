<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class CategoryController extends Controller {
    public function index( Request $request ) {
        //dd( auth()->user()->id );
        $searchText = '';
        if ( !empty( $request->get( 'searchText' ) ) ) {
            $searchText = $request->get( 'searchText' );
            $categories = Category::where( 'name', 'like', '%' . $searchText . '%' )->latest()->paginate( 5 );
        } else {
            $categories = Category::latest()->paginate( 10 );
        }

        return view( 'admin.category.index', compact( 'categories', 'searchText' ) );
    }

    public function create() {
        return view( 'admin.category.create' );
    }

    public function store( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ], [
            'name.required' => 'Tên không được trống',
            'slug.required' => 'Slug không được trống',
            'slug.unique' => 'Tên đã được sử dụng'
        ] );
        if ( $validator->passes() ) {
            $category = new Category();
            $category->name = $request->get( 'name' );
            $category->slug = $request->get( 'slug' );
            $category->status = $request->get( 'status' );
            $category->save();

            // Save image
            if ( !empty( $request->image_id ) ) {
                $tempImage = TempImage::find( $request->image_id );
                $ext = explode( '.', $tempImage->name );
                $ext = last( $ext );

                $newImageName = $category->id . '.' . $ext;
                $sPath = public_path( '/temp/' . $tempImage->name );
                $dPath = public_path( '/uploads/category/' . $newImageName );
                File::copy( $sPath, $dPath );

                // Generate Image Thumbnail
                //                $dPath2 = public_path( '/uploads/category/thumb/'.$newImageName );
                //                $manager = ImageManager::imagick();
                //                $img = $manager->read( $sPath );
                //                $img->scale( 450, 600 );
                //                $img->save( $dPath2 );

                $category->image = $newImageName;
                $category->save();
            }

            $request->session()->flash( 'success', 'Đã thêm danh mục thành công.' );

            return response()->json( [
                'status' => true,
                'message' => 'Category added successfully.'
            ] );

        } else {
            return response()->json( [
                'status' => false,
                'errors' => $validator->errors()
            ] );
        }
    }

    public function edit( $id, Request $request ) {
        $category = Category::find( $id );
        if ( empty( $category ) ) {
            return redirect()->route( 'admin.category.index' );
        }
        return view( 'admin.category.edit', compact( 'category' ) );
    }

    public function update( $id, Request $request ) {
        $category = Category::find( $id );
        if ( empty( $category ) ) {
            return response()->json( [
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found.'
            ] );
        }

        $validator = Validator::make( $request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ] );
        if ( $validator->passes() ) {
            $category->name = $request->get( 'name' );
            $category->slug = $request->get( 'slug' );
            $category->status = $request->get( 'status' );
            $category->save();

            $old_image = $category->image;

            if ( !empty( $request->image_id ) ) {
                $tempImage = TempImage::find( $request->image_id );
                $ext = explode( '.', $tempImage->name );
                $ext = last( $ext );

                $newImageName = $category->id . '-' . time() . '.' . $ext;
                $sPath = public_path( '/temp/' . $tempImage->name );
                $dPath = public_path( '/uploads/category/' . $newImageName );
                File::copy( $sPath, $dPath );

                $category->image = $newImageName;
                $category->save();

                // remove old image
                File::delete( public_path( '/uploads/category/' . $old_image ) );
            }
            $request->session()->flash( 'success', 'Đã cập nhật danh mục thành công.' );
            return response()->json( [
                'status' => true,
                'message' => 'Category updated successfully.'
            ] );
        } else {
            return response()->json( [
                'status' => false,
                'errors' => $validator->errors()
            ] );
        }
    }

    public function destroy( $id, Request $request ) {
        $category = Category::find( $id );
        if ( empty( $category ) ) {
            $request->session()->flash( 'error', 'Category not found.' );
            return response()->json( [
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found.'
            ] );
            //            return redirect()->route( 'admin.category.index' );
        }
        File::delete( public_path( '/uploads/category/' . $category->image ) );
        $category->delete();

        $request->session()->flash( 'success', 'Đã xóa danh mục.' );

        return response()->json( [
            'status' => true,
            'message' => 'Category deleted successfully.'
        ] );
    }
}