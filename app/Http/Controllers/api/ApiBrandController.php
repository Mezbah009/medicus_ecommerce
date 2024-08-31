<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiBrandController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $brands = Brand::all();
        return response()->json($brands,status: 200);

    }

    public function indexById($brandId): \Illuminate\Http\JsonResponse
    {
        $brand = Brand::find($brandId);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }
        return response()->json($brand,status: 200);

    }


    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if ($validator->passes()) {
            $brands = new Brand();
            $brands->name = $request->input('name');
            $brands->slug = $request->input('slug');
            $brands->status = $request->input('status', 'active'); // Assuming 'status' is optional with a default value

            $brands->save();
            return response()->json(['message' => 'Brand created successfully'], 201);
        } else {
            // Validation failed, return validation errors.
            return response()->json($validator->errors(), 400);
        }
    }

    // ei brand id tah extra pathaite hbe put request e
    public function update(Request $request, $brandId): \Illuminate\Http\JsonResponse
    {
        $brand = Brand::find($brandId); // find kore oi id er brand khuje ber korchi

        if (!$brand) { // jodi oi id te data na thake toh null ashbe sekahne error / bad request response jasse
            return response()->json(['message' => 'Brand not found'], 404);
        }
// data oi id te theke thakle validate kora hosse post kora new data tah
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brandId,
        ]);

        if ($validator->passes()) {
            $brand->name = $request->input('name');
            $brand->slug = $request->input('slug');
            $brand->status = $request->input('status', 1);

            $brand->save();
            return response()->json(['message' => 'Brand updated successfully'], 200);
        } else {
            // Validation failed, return validation errors.
            return response()->json($validator->errors(), 400);
        }
    }

    public function destroy($brandId): \Illuminate\Http\JsonResponse
    {
        $brand = Brand::find($brandId);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        $brand->delete();

        return response()->json(['message' => 'Brand deleted successfully'], 200);
    }

}
