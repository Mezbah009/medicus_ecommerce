<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request){
        $subCategories = SubCategory::select('sub_categories.*',"categories.name as categoryName")
        ->latest('id')
        -> leftJoin('categories','categories.id','sub_categories.category_id');
        // dd($subCategories);
        if(!empty($request->get('keyword'))){
                $subCategories =
                $subCategories->where('sub_categories.name','like','%'.$request->get('keyword').'%');
        }
        $subCategories = $subCategories->latest()->paginate(10);
        return view('admin.sub_category.list',compact('subCategories'));

    }

    public function create(){
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub_category.create', ['categories' => $categories]);
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories', // Assuming 'sub_categories' is your table name
            'category' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            $subcategory = new SubCategory();
            $subcategory->name = $request->input('name');
            $subcategory->slug = $request->input('slug');
            $subcategory->status = $request->input('status');
            $subcategory->showHome = $request->input('showHome'); // Assuming you have a 'showHome' field
            $subcategory->category_id = $request->input('category'); // Assuming you have a 'category_id' field

            $subcategory->save();

            $request->session()->flash('success', 'Sub-category created successfully');

            return response([
                'status' => true,
                'message' => 'Sub-category created successfully',
            ]);
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function edit($id, Request $request){
        $subCategory=SubCategory::find($id);
        if(empty($subCategory)){
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('sub-categories.index');
        }
        $categories = Category::orderBy('name', 'ASC')->get();
        // $data['categories'] = $categories;
        // $data['subCategory'] = $subCategory;
        // dd($categories);
        return view('admin.sub_category.edit', compact('categories','subCategory'));

    }

    public function update($id, Request $request){

        $subCategory = SubCategory::find($id);

        if (empty($subCategory)) {
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,' . $subCategory->id . ',id',
            'category' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            $subCategory->name = $request->input('name');
            $subCategory->slug = $request->input('slug');
            $subCategory->status = $request->input('status');
            $subCategory->showHome = $request->input('showHome');
            $subCategory->category_id = $request->input('category');
            $subCategory->save();

            return response([
                'status' => true,
                'message' => 'Sub-category updated successfully',
            ]);
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }



    public function destroy($id, Request $request){
        $subCategory=SubCategory::find($id);
        if(empty($subCategory)){
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('sub-categories.index');
        }
        $subCategory->delete();

        $request->session()->flash('success', 'Sub-category deleted successfully');
        return response([
            'status' => true,
            'message' => 'Sub-category deleted successfully',
        ]);
    }
}
