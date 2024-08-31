<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempImage;
use Image;

class TempImagesController extends Controller
{
    public function create(Request $request){

        $image =$request->image;

        if(!empty($image)){
            $ext =$image->getClientOriginalExtension();
            $newName = time().'.'. $ext;
            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();
            $image->move(public_path().'/temp',$newName);

            /*General Thumbnail*/
            $sourcePath = public_path('temp/' . $newName);
            $destPath = public_path('temp/thumb/' . $newName);

            if ($request->hasFile('image') && file_exists($sourcePath)) {
                $image = Image::make($sourcePath);
                $image->fit(300, 275);
                $image->save($destPath);

                return response()->json([
                    'status' => true,
                    'image_id' => $tempImage->id,
                    'imagePath' => asset('/temp/thumb/' . $newName),
                    'message' => 'Image uploaded successfully',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Unable to process the image. Please check the uploaded file.',
                ]);
            }

        }

    }
}
