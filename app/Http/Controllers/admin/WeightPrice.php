<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Weight;

class WeightPrice extends Controller
{
    public function index(Request $request){
        $weight = Weight::latest();
        if(!empty($request->get('keyword'))){
               $weight =
               $weight->where('name','like','%'.$request->get('keyword').'%');
        }
        $weight = $weight->latest()->paginate(10);
        return view('admin.weights.list', compact('weight'));
    }
    public function create(Request $request){
        return view('admin.weights.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'min_weight' => 'required',
            'max_weight' => 'required',
            'price' => 'required',

    ]);
    if($validator->passes()){
        $weight = new Weight();
        $weight -> min_weight = $request->min_weight;
        $weight -> max_weight = $request->max_weight;
        $weight -> price = $request->price;
        $weight->save();
        $message = 'Weight addeed successfully.';
        session()->flash ('success',$message);

        return response()->json([
            'status'  =>  true,
            'message' => $message
        ]);
    }else{
        return response()->json([
            'status'  =>  false,
            'errors'=>$validator->errors()
        ]);
    }
    }
    public function edit(Request $request,$id){
        $weight= Weight::find($id);
        return view('admin.weights.edit',compact('weight') );
    }


    public function update(Request $request,$id){
        $weight= Weight::find($id);

        if (empty($weight)){
            session()->flash('error','Page not found');
            return response()->json([
                'status'=> false,
                'message'=> 'page not found'
            ]);

        }
        $validator = Validator::make($request->all(),[
            'min_weight' => 'required',
            'max_weight' => 'required',
            'price' => 'required',
    
            ]);
            if($validator->passes()){
               $weight= Weight::find($id);
                $weight -> min_weight = $request->min_weight;
                $weight -> max_weight = $request->max_weight;
                $weight -> price = $request->price;
                $weight->save();
                $message = 'Weight Update successfully.';
                session()->flash ('success',$message);
    
                return response()->json([
                    'status'  =>  true,
                    'message' => $message
                ]);
            }else{
                return response()->json([
                    'status'  =>  false,
                    'errors'=>$validator->errors()
                ]);
                }
    }

    public function destroy($id, Request $request){
        $weight = Weight::find($id);

        if (empty($weight)){
            session()->flash('error','Weight not found');
            return response()->json([
                'status'=> false,
                'message'=> 'Weight not found'
            ]);

        }
        $weight->delete();
        $request->session()->flash('success','Weight Delete successfully');

        return response()->json([
            'status' => true,
            'message' => 'Weight Delete successfully',
        ]);

    }
}
