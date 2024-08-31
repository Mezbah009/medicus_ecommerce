<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Mail\ResetPasswordEmail;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;
use App\Models\CustomerAddress;
use App\Models\Country;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use PDF;




class AuthController extends Controller
{
    public function login()
    {
        return view('front.account.login');
    }
    public function register()
    {
        return view('front.account.register');
    }
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'  => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'password_confirmation' => 'required|same:password',
            'phone' => 'required|unique:users|digits:11'
        ]);
        if ($validator->passes()){
            $user =$request->all();
            $generate = $this->generate($request->phone);
            $user['opt'] =$generate->otp;


            $message = "your otp to login is ".$generate->otp;


            //return with otp
            return redirect()->route('account.otpVerify',$generate->id)
            ->with('user',$user)
            ->with('success', $message);
            // return response()->json([
            //     'status'=>true,
            //     'generate'=>$generate->id,
            //     'messege'=>'Profile updated successfully'
            // ]);
        }else {
            return redirect()->route('front.account.register')
            ->withErrors($validator)
            ->withInput();

        }

    }
    public function authenticate(Request $request){

        $validator = Validator::make($request->all(),[
            'phone' => 'required',
            'password' => 'required'
        ]);
        if($validator->passes()){
            // dd($request->all());
            $credentials = $request->only('phone', 'password');
            $credentials['is_otp_verified'] = 1;

            if(Auth::attempt($credentials, $request->get('remember'))){
                if (session()->has('url.intended')){
                    return redirect(session()->get('url.intended'));
                    }
            return redirect()->route('account.profile');


            }else{
                return redirect()->route('account.login')
                ->withInput($request->only('email'))
                ->with('error','Either email/password is incorrect');

            }

        }
        else{
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }
    public function profile(){
        $userID = Auth::user()->id;
        $countries = Country::orderBy('name','ASC')->get();
        $address= CustomerAddress::where('user_id',$userID)->first();
        $user = User::where('id', $userID)->first();
        return view('front.account.profile', compact('user','countries','address'));


    }
    public function updateProfile(Request $request){
        $userID = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users,email,' .$userID.',id',

    ]);

    if($validator->passes()){
        $user= User::find($userID);
        $user->name =  $request->name;
        $user->email= $request->email;
        $user->save();

        $request->session()->flash('success','Profile updated successfully');
        return response()->json([
            'status'=>true,
            'messege'=>'Profile updated successfully'
        ]);

    }else{
        return response()->json([
            'status'=>false,
            'errors'=>$validator->errors()
        ]);
    }

    }
    public function logout(){
        Auth:: logout();
        return redirect()->route('account.login')
        ->with('success','You successfully logged out!');
    }

    public function orders(){
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        $data['orders'] = $order;

        return view('front.account.order',$data);
    }

    public function orderDetails($id){
        $data = [];
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('id', $id)->first();
        $data['order'] = $order;

        $orderItems = OrderItem::where('order_id', $id)
            ->with('product_item')
            ->get();
        $data['orderItems'] = $orderItems;

        $orderItemsCount = OrderItem::where('order_id', $id)->count();
        $data['orderItemsCount'] = $orderItemsCount;

        return view('front.account.order-details',$data);
    }

    public function userDownloadPdf($id) {
        // Load the order and related data
        $order = Order::select('orders.*', 'countries.name as countryName')
            ->where('orders.id', $id)
            ->leftJoin('countries', 'countries.id', 'orders.country_id')
            ->first();

        $orderItems = OrderItem::where('order_id', $id)
            ->with('product_item')
            ->get();

        // Generate the PDF view
        $pdf = PDF::loadView('front.account.pdf', [
            'order' => $order,
            'orderItems' => $orderItems,
        ]);

        //dd($orderItems);
        // Generate a unique file name with the current timestamp
    $fileName = 'order_details_' . now()->format('Y-m-d_His') . '.pdf';

    return $pdf->download($fileName);
    }
    public function updateAddress(Request $request){
        $userID = Auth::user()->id;
        $users= CustomerAddress::where('user_id',$userID)->first();

        $validator = Validator::make($request->all(),[

            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,' .$userID.',id',
            'country_id' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
    ]);
    if($users !== null){
        $user= CustomerAddress::find($users->id);

    }else{
        $user= new CustomerAddress();
    }
    if($validator->passes()){

        $user->user_id = $userID;
        $user->first_name =  $request->first_name;
        $user->last_name= $request->last_name;
        $user->email= $request->email;
        $user->country_id= $request->country_id;
        $user->address= $request->address;
        $user->city= $request->city;
        $user->state= $request->state;
        $user->zip= $request->zip;
        $user->apartment= $request->apartment;
        $user->mobile= $request->mobile;
        $user->save();

        $request->session()->flash('success','Profile updated successfully');
        return response()->json([
            'status'=>true,
            'message'=>'Profile updated successfully'
        ]);

    }else{
        return response()->json([
            'status'=>false,
            'errors'=>$validator->errors()
        ]);
    }

    }

    public function wishlist(){
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->with(['product', 'product_items'])->get();

        $data['wishlists'] = $wishlists;
        return view('front.account.wishlist',$data);
    }

    public function removeProductFromWishList(Request $request){
        $wishlist = Wishlist::where('user_id',Auth::user()->id)->where('product_id',$request->id)->first();
        if($wishlist == null){

            session()->flash('error','Product already removed');
            return response()->json([
                'status' => true,
            ]);
        }else{
            $wishlist::where('user_id',Auth::user()->id)->where('product_id',$request->id)->delete();
            session()->flash('success','Product removed successfully');
            return response()->json([
                'status' => true,
            ]);
        }

    }
    public function showChangePasswordForm(){
        return view('front.account.change-password');
    }
    public function changePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',

    ]);
    $user = User::select('id','password')-> where('id',Auth::user()->id)->first();
    if($validator->passes()){
        if(!Hash::check($request->old_password,$user->password)){
            session()->flash('error','Your old password is incorrect, please try again.');
            return response()->json([
                'status'=>true
            ]);
        }
        User:: where('id',$user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
        session()->flash('success','Your  password is successfully updated');
            return response()->json([
                'status'=>true
            ]);

    }
    else{
        return response()->json([
            'status'=>false,
            'errors'=>$validator->errors()
        ]);
    }
}

    public function forgotPassword(){
        return view('front.account.forgot-password');
    }


    public function processForgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()){
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }

        $token = Str::random(60);

        DB:: table('password_reset_tokens')->where('email',$request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email'=> $request->email,
            'token' =>$token,
            'created_at' =>now()

        ]);

        //send Email Here
        $user = User::where('email',$request->email)->first();

        $formData =[
            'token'=> $token,
            'user' => $user,
            'mail_subject'=> 'You have requested to change your password'

        ];
        Mail::to($request->email)->send(new ResetPasswordEmail($formData));
        session()->flash('success','please check your inbox to reset your password');
        return response()->json([
            'status'=>true
        ]);
    }
    public function resetPassword($token){
        $tokenExist = DB::table('password_reset_tokens')->where('token',$token)->first();
        if($tokenExist== null){
        return redirect()->route('front.forgotPassword')->with('error','Invalid Request');
        }
        return view('front.account.reset-password',compact('token'));
    }
    public function processResetPassword(Request $request){
        $token = $request->token;
        $tokenObj = DB::table('password_reset_tokens')->where('token',$token)->first();
        if($tokenObj == null){
        return redirect()->route('front.forgotPassword')->with('error','Invalid Request');
        }
        $user = User::where('email',$tokenObj->email)->first();

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',

        ]);

        if ($validator->fails()){
            return response()->json([
                'status'=>false,
                'token'=>$token,
                'errors'=>$validator->errors()
            ]);
        }
        User::where('id',$user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        session()->flash('success','Your  password is successfully updated');
        return response()->json([
            'token'=>$token,
            'status'=>true
        ]);
    }

    public function otpLogin(){
        return view('front.account.otp-login');
    }
    public function generateOtp(Request $request){
        $validator = Validator::make($request->all(),[
            'phone' => 'required|exists:users,phone'
    ]);
    if($validator->passes()){


    $user= $request->all();
    $user = $user['phone'];

    // $generate = $this->generate($request->phone);

    // $message = "your otp to login is ".$generate->id;

    //return with otp
    session()->put('given_phone', $user);
    return redirect()->route('account.otpChangePassword');
    // return view('front.account.otp-change-password', compact('user'));
    // ->with('success', $message);
    // return view('front.account.otp-verify',compact('generate'))->with('success', $message);


    }
    else{
        return redirect()->route('account.otpLogin')
        ->with('error','Either email/password is incorrect');
    }


    }

    public function generate($mobile_num){

         // verify the the user has any otp there yet
         $userOtp = UserOtp::where('phone',$mobile_num)->latest()->first();
         $now = Carbon::now();

         //generate code
         return UserOtp::create([
            'phone' => $mobile_num,
            'otp'    => rand(123456,999999),
            'expire_at' => Carbon::now()->addMinutes(3),
         ]);


    }
    public function otpVerify($id){
        $user = session('user');

        return view('front.account.otp-verify',compact('id','user'));
    }
    public function otpRegister(Request $request){
        $userOtp = UserOtp::where('id',$request->otpID)->latest()->first();
        $now = Carbon::now();
        if($request->otp == $userOtp->otp &&  ($request->name !== null) && $now->lt($userOtp->expire_at)){
        $user= new User();
        $user->name =  $request->name;
        $user->email= $request->email;
        $user->phone= $request->phone;
        $user->is_otp_verified= 1;
        $user->password= Hash::make($request->password);
        $user->save();
        $credentials = $request->only('phone', 'password');
            if(Auth::attempt($credentials, $request->get('remember'))){
                if (session()->has('url.intended')){
                    return redirect(session()->get('url.intended'));
                    }
              return redirect()->route('account.profile');
              return redirect()->route('account.login')
               ->with('success', 'You are succussfully registered');
        }
        else{
            return redirect()->route('front.account.register')
            ->with('error', 'wrong otp');
        }
    }else if($request->otp == $userOtp->otp && ($request->new_password !== null) && $now->lt($userOtp->expire_at)){

        $userOtp = UserOtp::where('id',$request->otpID)->latest()->first();
        $user = User::where('phone',$userOtp->phone)->latest()->first();
        $credentials['phone'] = $userOtp->phone;
        $credentials['password'] = $request->new_password;
        User:: where('id',$user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        if(Auth::attempt($credentials, $request->get('remember'))){
            if (session()->has('url.intended')){
                return redirect(session()->get('url.intended'));
                }
          return redirect()->route('account.showChangePasswordForm')->with('success', 'You are succussfully registered');
            }
        }
    else{
            return redirect()->route('front.account.register')
            ->with('error', 'wrong otp');
        }
  }

  public function otpChangePassword(){
    $user=  session()->get('given_phone');
    return view('front.account.otp-change-password', compact('user'));


  }
  public function otpStorePassword(Request $request){
    $validator = Validator::make($request->all(),[
        'new_password' => 'required|min:5',
        'confirm_password' => 'required|same:new_password',
    ]);
    if ($validator->passes()){
        $user = $request->all();
        $generate = $this->generate($request->phone);

        $message = "your otp to login is ".$generate->id;
        session()->put('user',$user);


        return redirect()->route('account.otpVerifyForgetPassword',$generate->id)
        ->with('success', $message);
    }
    else{
        return redirect()->route('account.otpChangePassword')
        ->withErrors($validator)
        ->withInput();
    }



  }
  public function otpVerifyForgetPassword($id){

    $user = session('user');

    return view('front.account.otp-verify-forgetPassword',compact('id','user'));
}





}
