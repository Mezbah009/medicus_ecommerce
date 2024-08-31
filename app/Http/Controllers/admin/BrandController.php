<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(Request $request)
{
    $brands = Brand::latest('id'); // Assuming you have a 'Brand' model

    if (!empty($request->get('keyword'))) {
        $brands = $brands->where('name', 'like', '%' . $request->get('keyword') . '%');
    }

    $brands = $brands->latest()->paginate(10);

    return view('admin.brands.list', compact('brands'));
}

    public function create(){

        return view('admin.brands.create');

    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if ($validator->passes()) {

            $brands=new Brand();
            $brands->name=$request->name;
            $brands->slug=$request->slug;
            $brands->status=$request->status;

            $brands->save();
            $request->session()->flash('success','Brand added successfully');

            return response()->json([
                    'status' => true,
                    'message' => 'Brand created successfully',
            ]);

        } else {
            // Validation failed, return validation errors.
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
    public function edit($id, Request $request){
        $brand =Brand::find($id);

        if (empty($brand)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('brands.index');
        }

        $data['brand']=$brand;
        return view('admin.brands.edit',$data);
    }

    public function update($id, Request $request){


        $brand =Brand::find($id);

        if (empty($brand)){
            $request->session()->flash('error','Record not found');
            return response()->json([
                'status'=> false,
                'notFound'=> true
            ]);

        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id. 'id',
        ]);

        if ($validator->passes()) {

            $brands =Brand::find($id);
            $brands->name=$request->name;
            $brands->slug=$request->slug;
            $brands->status=$request->status;

            $brands->save();
            $request->session()->flash('success','Brand Updated successfully');

            return response()->json([
                    'status' => true,
                    'message' => 'Brand Updated successfully',
            ]);

        } else {
            // Validation failed, return validation errors.
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

    }

    public function destroy($id, Request $request){
        $brand =Brand::find($id);

        if (empty($brand)){
            return redirect()->route('brands.index');

        }
        $brand->delete();
        $request->session()->flash('success','Brand Delete successfully');

        return response()->json([
            'status' => true,
            'message' => 'Brand Delete successfully',
        ]);

    }


}
