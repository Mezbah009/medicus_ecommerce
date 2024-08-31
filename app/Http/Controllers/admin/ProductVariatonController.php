<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Variation;
use App\Models\VariationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductVariatonController extends Controller
{

    public function index(Request $request){
        $variations = Variation::latest();
    
        if(!empty($request->get('keyword'))){
            $variations = $variations->where('var_name','like','%'.$request->get('keyword').'%');
        }
    
        $variations = $variations->with('variationDetails')->get();
    
        return view('admin.variation.list', compact('variations'));
    }
    
 
    public function create()
    {
        return view('admin.variation.create');
    }

   
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'var_name' => 'required|unique:variations,var_name',
            'arr.*.var_option_name' => 'required|string|max:255',
            'arr.*.code' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    
        $variations = new Variation();
        $variations->var_name = $request->input('var_name');
        $variations->save();
    
        $arrData = $request->input('arr');
    
        foreach ($arrData as $data) {
            $optionName = $data['var_option_name'];
            $optionCode = $data['code'];
    
            $variation_details = new VariationDetail();
            $variation_details->var_option_name = $optionName;
            $variation_details->code = $optionCode;
    
            // Corrected: Associate VariationDetail with Variation
            $variations->variationDetails()->save($variation_details);
        }
    
        $request->session()->flash('success', 'Product variations added successfully');
        return response()->json([
            'status' => true,
            'message' => 'Product variations added successfully',
        ]);
    }


    public function show($id, Request $request)
    {
        $variation_details=VariationDetail::where('variation_id', $id);

        if(!empty($request->get('keyword'))){
            $variation_details = $variation_details->where('var_option_name','like','%'.$request->get('keyword').'%')
            ->orWhere('code', 'like', '%' . $request->get('keyword') . '%');;
        }
        $variation_details = $variation_details->get();
        if(empty($variation_details)){
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('variation.index');
        }
        return view('admin.variation.show', compact('variation_details', 'id'));
    }


    public function add($id)
    {
        return view('admin.variation.add',compact('id'));
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'arr.*.var_option_name' => 'required|string|max:255',
            'arr.*.code' => 'required|string|max:255',
            'variation_id' => 'required|exists:variations,id', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $arrData = $request->input('arr');
        $variationId = $request->input('variation_id');

        foreach ($arrData as $data) {
            $optionName = $data['var_option_name'];
            $optionCode = $data['code'];

            $variation_details = new VariationDetail();
            $variation_details->var_option_name = $optionName;
            $variation_details->code = $optionCode;
            $variation_details->variation_id = $variationId;
            $variation_details->save();
        }

        $request->session()->flash('success', 'Product variations added successfully');
        return response()->json([
            'status' => true,
            'message' => 'Product variation Option successfully',
        ]);
    }

    public function edit($id, Request $request)
    {
        $variation_details=VariationDetail::find($id);
        if(empty($variation_details)){
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('variation.index');
        }   
        return view('admin.variation.edit', compact('variation_details'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'var_option_name' => 'required',
            'code' => 'required'
        ]);

        if ($validator->passes()) {
            $variation_details = VariationDetail::find($id);
            $variation_details->var_option_name = $request->var_option_name;
            $variation_details->code = $request->code;
            $variation_details->save();

            session()->flash('success', 'Variation Option updated successfully');
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


     public function destroy($id, Request $request){
        $variation_details=VariationDetail::find($id);
        if(empty($variation_details)){
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('variation.index');
        }
        $variation_details->delete();

        $request->session()->flash('success', 'Variation option deleted successfully');
        return response([
            'status' => true,
            'message' => 'Variation option deleted successfully',
        ]);
    }
    
}
