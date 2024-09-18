<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "data" => Product::paginate()
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            "data" => $product
        ]);
    }



    //--------------------
    public function getProductsByCategory($categoryId)
{
    // Find the category with the given ID
    $category = Category::find($categoryId);

    // Check if the category exists
    if (empty($category)) {
        return response()->json([
            'status' => false,
            'message' => 'Category not found',
        ], 404);
    }

    // Fetch products associated with the category
    $products = $category->products()->get();

    // Return the products
    return response()->json([
        'status' => true,
        'category' => $category->name,
        'products' => $products,
    ]);
}

}
