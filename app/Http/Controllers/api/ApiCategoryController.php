<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Image;

class ApiCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();

        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $categories = $categories->latest()->paginate(10);
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->order = $request->order;
            $category->save();

            // Save image
            if (!empty($request->image_id)) {
                $this->handleImage($request->image_id, $category);
            }

            return response()->json(['status' => true, 'message' => 'Category added successfully']);
        }

        return response()->json(['status' => false, 'errors' => $validator->errors()]);
    }

    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            return response()->json(['status' => false, 'message' => 'Category not found']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id,
        ]);

        if ($validator->passes()) {
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->order = $request->order;
            $category->save();

            // Update image
            if (!empty($request->image_id)) {
                $this->handleImage($request->image_id, $category);
            }

            return response()->json(['status' => true, 'message' => 'Category updated successfully']);
        }

        return response()->json(['status' => false, 'errors' => $validator->errors()]);
    }

    public function destroy($categoryId)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            return response()->json(['status' => false, 'message' => 'Category not found']);
        }

        File::delete(public_path('/uploads/category/thumb/' . $category->image));
        File::delete(public_path('/uploads/category/' . $category->image));

        $category->delete();
        return response()->json(['status' => true, 'message' => 'Category deleted successfully']);
    }

    private function handleImage($imageId, $category)
    {
        $tempImage = TempImage::find($imageId);
        $extArray = explode('.', $tempImage->name);
        $ext = last($extArray);
        $newImageName = $category->id . '-' . time() . '.' . $ext;

        $sPath = public_path() . '/temp/' . $tempImage->name;
        $dPath = public_path() . '/uploads/category/' . $newImageName;
        File::copy($sPath, $dPath);

        // Generate image thumbnail
        $dPathThumb = public_path() . '/uploads/category/thumb/' . $newImageName;
        $img = Image::make($sPath);
        $img->fit(450, 600, function ($constraint) {
            $constraint->upsize();
        });
        $img->save($dPathThumb);

        $category->image = $newImageName;
        $category->save();

        File::delete(public_path() . '/uploads/category/thumb/' . $tempImage->name);
    }
}
