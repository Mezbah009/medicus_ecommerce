<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductItem;
use App\Models\Variation;
use App\Models\VariationDetail;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Image;



class ProductItemController extends Controller
{
    public function saveProduct(){
        $variation = Variation::orderBy('var_name', 'ASC')->get();
        $color = VariationDetail::where('variation_id', 1)->get();
        $size = VariationDetail::where('variation_id', 2)->get();
        $product_id = 1;

        return view('admin.products.variation.create', compact('variation','color','size','product_id'));

    }
    public function create($id){
        $variation = Variation::orderBy('var_name', 'ASC')->get();
        $color = VariationDetail::where('variation_id', 1)->get();
        $size = VariationDetail::where('variation_id', 2)->get();
        $product_id = $id;

        // foreach($variation as $variations){
        //     $varName= $variations->id;
        //     $data[$varName] = VariationDetail::where('variation_id', $variations->id)
        //         ->get();

        return view('admin.products.variation.create', compact('variation','color','size','product_id'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'arr.*.var_color_id' => 'required',
            'arr.*.var_size_id' => 'required',
            'arr.*.price' => 'required',
            'arr.*.compare_price' => 'required',
            'arr.*.quantity' => 'required',
            'arr.*.sku' => 'required',
            'arr.*.status' => 'required',
            'arr.*.image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        if ($request->product_id) {
            $product = Product::find($request->product_id);

            if ($product) {
                $product->has_variation = 1;
                $product->price = 0.00;
                $product->compare_price = Null;
                $product->save();
            }
        }

        $arrData = $request->input('arr');
        $imageFiles = $request->file('arr.*.image');

        foreach ($arrData as $index => $data) {
            if ($data['status'] == 0) {
                // Skip saving this item
                continue;
            }
            $productItem = new ProductItem();
            $productItem->product_id = $request->product_id;

            // Check for 'null' values
            $colorData = ($data['var_color_id'] !== '[null]') ? [
                'id' => $data['var_color_id'],
                'name' => $data['var_color'],
                'code' => $data['var_code_color']
            ] : Null;

            $sizeData = ($data['var_size_id'] !== '[null]') ? [
                'id' => $data['var_size_id'],
                'name' => $data['var_size'],
                'code' => $data['var_code_size']
            ] : Null;


            if (json_encode([$sizeData]) === '[{"id":"null","name":"null","code":"null"}]') {
                $sizeData = Null;
                $productItem->variation_size = json_encode($sizeData, JSON_UNESCAPED_UNICODE);
            }
            else{
                $productItem->variation_size = json_encode([$sizeData], JSON_UNESCAPED_UNICODE);
            }
            if (json_encode([$colorData]) === '[{"id":"null","name":"null","code":"null"}]') {
                $colorData = Null;
                $productItem->variation_color = json_encode($colorData, JSON_UNESCAPED_UNICODE);
            }
            else{
                $productItem->variation_color = json_encode([$colorData], JSON_UNESCAPED_UNICODE);
            }

            $productItem->price = $data['price'];
            $productItem->compare_price = $data['compare_price'];
            $productItem->sku = $data['sku'];
            $productItem->status = $data['status'];
            $productItem->qty = $data['quantity'];

        // Explicitly set a default value for the 'image' column
            $productItem->image = 'default_image.jpg'; // Replace with your default image filename

            $productItem->save();


            if (!empty($imageFiles[$index])) {
                foreach ($imageFiles[$index] as $imageIndex => $tempImage) {
                    if ($tempImage->getError() !== UPLOAD_ERR_OK) {
                        return response()->json([
                            'status' => false,
                            'error' => 'File upload error: ' . $tempImage->getErrorMessage(),
                        ]);
                    }

                    $ext = $tempImage->getClientOriginalExtension();
                    $newImageName = $productItem->id . '_' . $imageIndex . '.' . $ext;

                    try {
                        $tempImage->move(public_path('/uploads/product/item/'), $newImageName);
                    } catch (\Exception $e) {
                        return response()->json([
                            'status' => false,
                            'error' => 'Error moving the uploaded file: ' . $e->getMessage(),
                        ]);
                    }

                    $productItem->image = $newImageName;
                    $productItem->save();
                }
            }
        }

        $request->session()->flash('success', 'Product variations added successfully');

        return response()->json([
            'status' => true,
            'message' => 'Product variations added successfully',
        ]);
    }

}
