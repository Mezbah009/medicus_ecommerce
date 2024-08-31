<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Image;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {
        $image = $request->image; // Use file() to get the uploaded file
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathname();

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = null; // Use null instead of 'NULL'
        $productImage->save();

        $imageName = $request->product_id . '-' . $productImage->id . '-' . time() . '.' . $ext;
        $productImage->image = $imageName;
        $productImage->save();

        // Large Image
        $destPathLarge = public_path('uploads/product/large/' . $imageName);
        $imageLarge = Image::make($sourcePath);
        $imageLarge->resize(1400, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $imageLarge->save($destPathLarge);

        // Small Image
        $destPathSmall = public_path('uploads/product/small/' . $imageName);
        $imageSmall = Image::make($sourcePath);
        $imageSmall->fit(300, 300);
        $imageSmall->save($destPathSmall);

        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'imagePath' => asset('uploads/product/small/' . $productImage->image),
            'message' => 'Product added successfully', // Fixed the message
        ]);
    }
    public function destroy(Request $request){

        $productImage = ProductImage::find($request->id);

        if (empty($productImage)){
            return response()->json([
                'status' => false,
                'message' => 'Image not found'
            ]);
        }
        //Delete image from folder
        File::delete(public_path().'/uploads/product/large/'.$productImage->image);
        File::delete(public_path().'/uploads/product/small/'.$productImage->image);
        File::delete(public_path().'/uploads/product/'.$productImage->image);
        $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully'
        ]);
    }
}
