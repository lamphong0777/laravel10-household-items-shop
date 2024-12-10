<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Time;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->file('image');
        if(!empty($image)){
            $ext = $image->getClientOriginalExtension();
            $filename = $image->getClientOriginalName();
//            $newName = time().'.'.$filename.'.'.$ext;
            $newName = time().'.'.$filename;

            // save temp image to the database
            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path().'/temp/', $newName);

            // Generate thumbnail
            return response()->json([
                'success' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/'.$newName),
                'message' => 'Image uploaded successfully.'
            ]);
        }
    }
}
