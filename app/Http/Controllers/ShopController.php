<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\SubCategory;
use App\Models\Variation;
use App\Models\VariationDetail;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null){
        $categorySelected = '';
        $subCategorySelected = '';
        $brandsArray = [];
        $categories = Category::orderBy('id','ASC')->with('sub_category')->where('status',1)->get();
        $brands = Brand::orderBy('name','ASC')->where('status',1)->get();
        $products = Product::where('status',1)->with('product_items');

        // Apply filters here

        //Category filter
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            if (!empty($category)) {
                $products = $products->where('category_id', $category->id);
                $categorySelected = $category->id;
            }
        }

        // Sub-Category filter
        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            if (!empty($subCategory)) {
                $products = $products->where('sub_category_id', $subCategory->id);
                $subCategorySelected = $subCategory->id;
            }
        }

        // Brand filter
        if(!empty($request->get('brands'))){
            $brandsArray = explode(',', $request->get('brands'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }

        // Price filter
        if($request->get('price_max') != '' && $request->get('price_min') != ''){
            if($request->get('price_max') == 1000){
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 10000000]);
            } else{
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }

        }

        //Search by Product
        if(!empty($request->get('search'))){
            $products = $products->where('title', 'like', '%'.$request->get('search').'%');
        }

        $data['priceMax'] = (intval($request->get('price_max'))==0) ? 1000 : (intval($request->get('price_max'))) ;
        $data['priceMin'] = intval($request->get('price_min'));
        $data['sort'] = $request->get('sort');

        // Sorting filter
        if($request->get('sort')!= ''){
            if($request->get('sort') == 'latest'){
                $products = $products->orderBy('id','DESC');
            } else if ($request->get('sort') == 'price_asc'){
                $products = $products->orderBy('price','ASC');
            }else {
                $products = $products->orderBy('price','DESC');
            }
        }else{
            $products = $products->orderBy('id','DESC');
        }
        $products = $products->latest()->paginate(6);

        return view('front.shop', $data, compact('categories','brands','products', 'categorySelected', 'subCategorySelected', 'brandsArray'));

    }

    public function product($slug){
        // echo $slug;
        $product = Product::where('slug',$slug)
        ->withCount('product_ratings')
        ->withSum('product_ratings','rating')
        ->with(['product_images','product_ratings','product_items', 'brand'])
        ->first();

//        dd($product);
        if ($product->has_variation == 1) {
            $data['variationData'] = [];
            $hasSizes = false;
            $hasColors = false;

            foreach ($product->product_items as $productItem) {
                $key = $productItem->id;
                $variationColor = json_decode($productItem->variation_color);
                $variationSize = json_decode($productItem->variation_size);

                $data['variationData'][$key]['product-item-id'] = $productItem->id;
                $data['variationData'][$key]['price'] = $productItem->price;
                $data['variationData'][$key]['compare'] = $productItem->compare_price;
                $data['variationData'][$key]['image'] = $productItem->image;
                $data['variationData'][$key]['qty'] = $productItem->qty;
                $data['variationData'][$key]['color'] = $variationColor;
                $data['variationData'][$key]['size'] = $variationSize;

                // Check if the current product item has sizes
                if (!empty($variationSize)) {
                    $hasSizes = true;
                }
                //dd($variationColor);
                if (!empty($variationColor)) {
                    $hasColors = true;
                }

            }
            //dd($hasSizes);
            // Set the $hasSizes variable after the loop
            $data['hasSizes'] = $hasSizes;
            $data['hasColors'] = $hasColors;
        }



        if($product == null){
            abort(404);
        }
        $relatedProducts = [];

        // Fetch related products
        if (!empty($product->related_products)) {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->where('status',1)->get();
        }

            $data['product'] = $product;
            $data['relatedProducts'] = $relatedProducts;

        $avgRating = '0.00';
        $avgRatingPer = '0';

        if($product->product_ratings_count > 0){
            $avgRating = number_format(($product->product_ratings_sum_rating/$product->product_ratings_count),2);
            $avgRatingPer = $avgRating*100/5;

        }
        $data['avgRating'] = $avgRating;
        $data['avgRatingPer'] = $avgRatingPer;


        return view('front.product',$data);

    }

    public function saveRating($id, Request $request){
        $validator = Validator::make($request->all(),[
            'name' =>'required|min:5',
            'email' =>'required|email',
            'comment' =>'required',
            'rating' =>'required'

        ]);
        if($validator->fails()){
            return response()->json([
                'status' =>false,
                'errors' =>$validator->errors()
            ]);
        }

        $count = ProductRating::where('email',$request->email)->count();
        if ($count > 0){
            session()->flash('error','You already rated this product.');
            return response()->json([
                'status' =>true,
            ]);
        }

        $productRating = new ProductRating;
        $productRating->product_id = $id;
        $productRating->username = $request->name;
        $productRating->email = $request->email;
        $productRating->comment = $request->comment;
        $productRating->rating = $request->rating;
        $productRating->status = 0;
        $productRating->save();

        session()->flash('success','Thanks for your rating.');

        return response()->json([
        'status' =>true,
        'message' => 'Thanks for your rating.'
    ]);

    }
}
