<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function update(Request $request) {
        $image = $request->file('image');
        $ext = $image->getClientOriginalExtension();
        $sPath = $image->getPathname();

        $productImage = new ProductImage();
        $productImage->product_id = $request->get('product_id');
        $productImage->image = 'NULL';
        $productImage->save();

        $imageName = $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
        $productImage->image = $imageName;
        $productImage->save();

        // Large image
        $dPath = public_path().'/uploads/products/large/'.$imageName;
        File::copy($sPath, $dPath);

        return response()->json([
            'status' => true,
            'ImagePath' => asset('uploads/products/large/'.$productImage->image),
            'image_id' => $productImage->id,
            'message' => 'Image uploaded successfully',
        ]);
    }

    public function delete(Request $request) {
        $productImage = ProductImage::find($request->id);

        if(empty($productImage)) {
            return response()->json([
                'status' => false,
                'message' => 'Image not found',
            ]);
        }

        File::delete(public_path().'/uploads/products/large/'.$productImage->image);
        $productImage->delete();
        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully',
        ]);
    }
}
