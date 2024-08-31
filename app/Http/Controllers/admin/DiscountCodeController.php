<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\DiscountCoupon;

use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index(Request $request){
        $discountCoupons = DiscountCoupon::latest();
        if(!empty($request->get('keyword'))){
               $discountCoupons =
               $discountCoupons->where('name','like','%'.$request->get('keyword').'%');
        }
        $discountCoupons = $discountCoupons->latest()->paginate(10);
        return view('admin.coupon.list',compact('discountCoupons'));

    }

    public function create(){
        return view('admin.coupon.create');
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required',
            'status' => 'required',
    ]);
    if($validator->passes()){

        //starting date must be greater than current date
        if(!empty($request->starts_at)){
            $now = Carbon::now();
            $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);

            if($startAt->lte($now)== true){
                return response()->json([
                    'status'=>false,
                    'errors'=> ['starts_at'=> 'Start date can not be less than current date time']
                ]);

            }
        }

        //expiring date must be greater than start date
        if(!empty(($request->expires_at) &&  ($request->starts_at))){
            $now = Carbon::now();
            $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->expires_at);
            $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);


            if($expiresAt->gt($startAt) == false){
                return response()->json([
                    'status'=>false,
                    'errors'=> ['expires_at'=> 'Expire date must be greater than Start date']
                ]);

            }
        }

        $discountCode = new DiscountCoupon();
        $discountCode->code =$request->code;
        $discountCode->name =$request->name;
        $discountCode->description =$request->description;
        $discountCode->max_uses =$request->max_uses;
        $discountCode->max_uses_user =$request->max_uses_user;
        $discountCode->type =$request->type;
        $discountCode->discount_amount =$request->discount_amount;
        $discountCode->min_amount =$request->min_amount;
        $discountCode->status =$request->status;
        $discountCode->starts_at =$request->starts_at;
        $discountCode->expires_at =$request->expires_at;
        $discountCode->save();

        $message = 'Discount coupon addeed successfully.';
        session()->flash ('success',$message);

        return response()->json([
            'status'  =>  true,
            'message' => $message
        ]);
      
    }else{
        return response()->json([
            'status'=>false,
            'errors'=>$validator->errors()
        ]);
    }
    }
    public function edit(Request $request,$couponId){
        $coupon = DiscountCoupon::find($couponId);
        if(empty($coupon)){
            session()->flash('error','Record not found');
            return redirect()->route('coupons.index');
        }
        return view('admin.coupon.edit',compact('coupon'));
        
    }
    public function update(Request $request,$couponId){

        $discountCode = DiscountCoupon::find($couponId);
        if($discountCode== null){
            session()->flash('error','Record not found');
            return response()->json([
                'status' => true
            ]);
        }

        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required',
            'status' => 'required',
    ]);
    if($validator->passes()){

       
        //expiring date must be greater than start date
        if(!empty(($request->expires_at) &&  ($request->starts_at))){
            $now = Carbon::now();
            $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->expires_at);
            $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);


            if($expiresAt->gt($startAt) == false){
                return response()->json([
                    'status'=>false,
                    'errors'=> ['expires_at'=> 'Expire date must be greater than Start date']
                ]);

            }
        }

        $discountCode->code =$request->code;
        $discountCode->name =$request->name;
        $discountCode->description =$request->description;
        $discountCode->max_uses =$request->max_uses;
        $discountCode->max_uses_user =$request->max_uses_user;
        $discountCode->type =$request->type;
        $discountCode->discount_amount =$request->discount_amount;
        $discountCode->min_amount =$request->min_amount;
        $discountCode->status =$request->status;
        $discountCode->starts_at =$request->starts_at;
        $discountCode->expires_at =$request->expires_at;
        $discountCode->save();

        $message = 'Discount coupon Updated successfully.';
        session()->flash ('success',$message);

        return response()->json([
            'status'  =>  true,
            'message' => $message
        ]);
      
    }else{
        return response()->json([
            'status'=>false,
            'errors'=>$validator->errors()
        ]);
    }
    }
    public function destroy($couponId){
        $couponId= DiscountCoupon::find($couponId);
        if(empty($couponId)){

        $request->session()->flash('Error','Coupon Code not Found');
        return response()->json([
            'status'=>true,
            'message' =>'Coupon Code not Found'
        ]);
        }
        $couponId->delete();

        session()->flash('success','Coupon Code deleted successfully');
        return response()->json([
            'status'=>true,
            'message' =>'Coupon Code deleted successfully'
        ]);
   
   
    }
}
