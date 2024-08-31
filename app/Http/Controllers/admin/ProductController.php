<?php

namespace App\Http\Controllers\admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');
        if(!empty($request->get('keyword'))){
            $products = $products->where('title','like','%'.$request->get('keyword').'%');
        }
        $products = $products->paginate(10);
        return view('admin.products.list',compact('products'));
    }
    public function create(){
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
       // dd($data);
        return view('admin.products.create',$data);
    }

    public function store(Request $request) { 
        if($request->payment_method==1){
            $rules = [
                'title' => 'required',
                'slug' => 'required|unique:products',
                'price' => 'required|numeric',
                'sku' => 'required|unique:products',
                'track_qty' => 'required|in:Yes,No',
                'category' => 'required|numeric',
                'is_featured' => 'required|in:Yes,No',
            ];
    
            if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
                $rules['qty'] = 'required|numeric';
            }
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->passes()) {
                $product = new Product();
                $product->title=$request->title;
                $product->slug=$request->slug;
                $product->description=$request->description;
                $product->price=$request->price;
                $product->compare_price=$request->compare_price;
                $product->sku=$request->sku;
                $product->barcode=$request->barcode;
                $product->track_qty=$request->track_qty;
                $product->qty=$request->qty;
                $product->weight=$request->weight;
                $product->status=$request->status;
                $product->category_id=$request->category;
                $product->sub_category_id=$request->sub_category;
                $product->brand_id=$request->brand;
                $product->is_featured=$request->is_featured;
                $product->short_description = $request->short_description;
                $product->shipping_returns = $request->shipping_returns;
                $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
                $product->save();
               
    //            save gallery pic
                if (!empty($request->image_array)) {
                    foreach ($request->image_array as $temp_image_id) {
                        $tempImageInfo = TempImage::find($temp_image_id);
                        $extArray = explode('-', $tempImageInfo->name);
                        $ext = last($extArray); //like jpg, png, gif etc
    
                        $productImage = new ProductImage();
                        $productImage->product_id = $product->id;
                        $productImage->image = 'NULL';
                        $productImage->save();
    
                        $imageName = $product->id . '-' . $productImage->id.'-'.time(). '-' . $ext; // Fix the variable name here
    
                        $productImage->image = $imageName;
                        $productImage->save();
    
                        // Move the image from temp to uploads/product
                        $sourcePath = public_path('/temp/' . $tempImageInfo->name);
                        $destPath = public_path('/uploads/product/' . $imageName);
                        if (file_exists($sourcePath)) {
                            rename($sourcePath, $destPath); // Move the file
                        }
    
                        // Generate Product thumbnails
                        // Large Image
                        $sourcePath = public_path('/uploads/product/' . $imageName);
                        $destPathLarge = public_path('/uploads/product/large/' . $imageName);
                        $image = Image::make($sourcePath);
                        $image->resize(1400, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $image->save($destPathLarge);
    
                        // Small Image
                        $destPathSmall = public_path('/uploads/product/small/' . $imageName);
                        $image = Image::make($sourcePath);
                        $image->fit(300, 300);
                        $image->save($destPathSmall);
                    }
                }
    
                $request->session()->flash('success', 'Products added successfully');
    
                return response()->json([
                    'status' => true,
                    'has_variation'=>1,
                    'product_id'=>$product->id,
                    'message' => 'Products added successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }

        }else{
            $rules = [
                'title' => 'required',
                'slug' => 'required|unique:products',
                'price' => 'required|numeric',
                'sku' => 'required|unique:products',
                'track_qty' => 'required|in:Yes,No',
                'category' => 'required|numeric',
                'is_featured' => 'required|in:Yes,No',
            ];
    
            if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
                $rules['qty'] = 'required|numeric';
            }
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->passes()) {
                $product = new Product();
                $product->title=$request->title;
                $product->slug=$request->slug;
                $product->description=$request->description;
                $product->price=$request->price;
                $product->compare_price=$request->compare_price;
                $product->sku=$request->sku;
                $product->barcode=$request->barcode;
                $product->track_qty=$request->track_qty;
                $product->qty=$request->qty;
                $product->weight=$request->weight;
                $product->status=$request->status;
                $product->category_id=$request->category;
                $product->sub_category_id=$request->sub_category;
                $product->brand_id=$request->brand;
                $product->is_featured=$request->is_featured;
                $product->short_description = $request->short_description;
                $product->shipping_returns = $request->shipping_returns;
                $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
                $product->save();
               
    //            save gallery pic
                if (!empty($request->image_array)) {
                    foreach ($request->image_array as $temp_image_id) {
                        $tempImageInfo = TempImage::find($temp_image_id);
                        $extArray = explode('-', $tempImageInfo->name);
                        $ext = last($extArray); //like jpg, png, gif etc
    
                        $productImage = new ProductImage();
                        $productImage->product_id = $product->id;
                        $productImage->image = 'NULL';
                        $productImage->save();
    
                        $imageName = $product->id . '-' . $productImage->id.'-'.time(). '-' . $ext; // Fix the variable name here
    
                        $productImage->image = $imageName;
                        $productImage->save();
    
                        // Move the image from temp to uploads/product
                        $sourcePath = public_path('/temp/' . $tempImageInfo->name);
                        $destPath = public_path('/uploads/product/' . $imageName);
                        if (file_exists($sourcePath)) {
                            rename($sourcePath, $destPath); // Move the file
                        }
    
                        // Generate Product thumbnails
                        // Large Image
                        $sourcePath = public_path('/uploads/product/' . $imageName);
                        $destPathLarge = public_path('/uploads/product/large/' . $imageName);
                        $image = Image::make($sourcePath);
                        $image->resize(1400, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $image->save($destPathLarge);
    
                        // Small Image
                        $destPathSmall = public_path('/uploads/product/small/' . $imageName);
                        $image = Image::make($sourcePath);
                        $image->fit(300, 300);
                        $image->save($destPathSmall);
                    }
                }
    
                $request->session()->flash('success', 'Products added successfully');
    
                return response()->json([
                    'status' => true,
                    'message' => 'Products added successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
        }
       
    }
    public function edit($id, Request $request){
        $products = Product::find($id);
        if(empty($products)){

            $request->session()->flash('error', 'Product not found');

            return redirect()->route('products.index')->with('error','product not found');
        }
        $subCategories = SubCategory::where('category_id', $products->category_id)->get();
        /*fetch product image*/
        $productImages = ProductImage::where('product_id',$products->id)->get();


        $relatedProducts = [];

        // Fetch related products
        if (!empty($products->related_products)) {
            $productArray = explode(',', $products->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->with('product_images')->get();
        }



        $data = [];
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['product'] = $products;
        $data['subCategories'] = $subCategories;
        $data['productImages'] = $productImages;
        $data['$relatedProducts'] = $relatedProducts;



        return view('admin.products.edit',$data);
    }

    public function update($id, Request $request) {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id . ',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,' . $product->id . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Update the product data
        $product->title = $request->title;
        $product->slug = $request->slug;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->compare_price = $request->compare_price;
        $product->sku = $request->sku;
        $product->barcode = $request->barcode;
        $product->track_qty = $request->track_qty;
        $product->qty = $request->qty;
        $product->weight=$request->weight;
        $product->status = $request->status;
        $product->category_id = $request->category;
        $product->sub_category_id = $request->sub_category;
        $product->brand_id = $request->brand;
        $product->is_featured = $request->is_featured;
        $product->short_description = $request->short_description;
        $product->shipping_returns = $request->shipping_returns;
        $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';

        $product->save();


        $request->session()->flash('success', 'Product updated successfully');

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully'
        ]);
    }

    public function destroy($id, Request $request)
    {
        $product = Product::find($id);

        if (empty($product)) {
            $request->session()->flash('error', 'Product not found');
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }

        $productImages = ProductImage::where('product_id', $id)->get();

        if (!$productImages->isEmpty()) {
            foreach ($productImages as $productImage) {
                File::delete(public_path().'/uploads/product/large/'.$productImage->image);
                File::delete(public_path().'/uploads/product/small/'.$productImage->image);
                File::delete(public_path().'/uploads/product/'.$productImage->image);
            }

            ProductImage::where('product_id', $id)->delete();
        }

        $product->delete();

        $request->session()->flash('success', 'Product deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }


    public function getProducts(Request $request){

        $tempProduct=[];
        if($request->term !=""){
            $products = Product::where('title','like', '%' .$request->term.'%')->get();
            if ($products !=null){
                foreach ($products as $product){
                    $tempProduct[]= array('id' => $product->id, 'text'=>$product->title);
                }
            }
        }
        return response()->json([
            'tags'=>$tempProduct,
            'status'=>true
        ]);
    }

}
