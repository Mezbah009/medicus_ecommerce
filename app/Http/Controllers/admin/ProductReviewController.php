<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductRating;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index(Request $request){
        $product_ratings = ProductRating::latest();

        if(!empty($request->get('keyword'))){
            $product_ratings = $product_ratings->where('username','like','%'.$request->get('keyword').'%');
            $product_ratings = $product_ratings->where('email','like','%'.$request->get('keyword').'%');


        }
        $product_ratings = $product_ratings->paginate(10);

        return view('admin.product_review.list',[
            'product_ratings' =>$product_ratings
        ]);

    }

    public function toggleStatus(Request $request)
{
    // Validate the request if needed

    $productRating = ProductRating::find($request->id);


    if ($productRating) {
        $productRating->status = $request->status;
        $productRating->save();

        return response()->json(['status' => true]);
    }

    return response()->json(['status' => false, 'message' => 'Product review not found.']);
}

}
