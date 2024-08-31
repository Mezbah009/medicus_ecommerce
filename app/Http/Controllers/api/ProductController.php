<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}
