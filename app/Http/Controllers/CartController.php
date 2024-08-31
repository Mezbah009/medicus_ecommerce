<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\Weight;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\ShippingCharge;
use Gloudemans\Shoppingcart\Facades\Cart;
use http\Message;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class CartController extends Controller
{
    public function addToCart(Request $request){

        $product = Product::with(['product_images'])->find($request->id);

        if ($product == null){
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ]);
        }

            if (Cart::count() > 0){
                $cartContent = Cart::content();
               // dd($cartContent);
                $productAlreadyExist = false;
                foreach ($cartContent as $item){
                    //dd($item);
                    if($item->id == $product->id){
                        $productAlreadyExist = true;
                    }
                }

                if ($productAlreadyExist == false){
                    Cart::add($product->id, $product->title, 1, $product->price,[
                        'productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '',
                        'productSku' => $product->sku
                    ]);
                    $status = true;
                    $message = $product->title.' added in your cart successfully';
                    session()->flash('success', $message);
                }
                else{
                    $status = false;
                    $message = $product->title.' already added in cart';
                }
            }
            else{
                Cart::add($product->id, $product->title, 1, $product->price,[
                    'productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '',
                    'productSku' => $product->sku
                ]);
                $status = true;
                $message = $product->title.' added in your cart successfully';
                session()->flash('success', $message);
            }

            $cartContentWithSku = Cart::content()->map(function ($item) {
                $item->sku = isset($item->options['productSku']) ? $item->options['productSku'] : null;
                return $item;
            });
            return response()->json([
                'status' => $status,
                'message' => $message,
                'cartContent' => $cartContentWithSku
            ]);

    }

    public function addItemToCart(Request $request)
    {
        if($request->input('product-item-id') != null){

            $product = ProductItem::with('product')->find($request->input('product-item-id'));
        }
    else{
            $product = ProductItem::with('product')->find($request->id);
        }

        if ($product) {
            if (Cart::count() > 0) {
                $cartContent = Cart::content();
                $productAlreadyExist = false;

                foreach ($cartContent as $item) {
                    if ($item->productItemId == $product->id) {
                        $productAlreadyExist = true;
                        break;
                    }
                }

                if (!$productAlreadyExist) {
                    // Accessing the 'product' relationship
                    Cart::add($product->product->id, $product->product->title, 1, $product->price, [
                        'productItemId' => $product->id,
                        'productSku' => $product->sku,
                        'productImage' => $product->image,
                        'productVariationColor' => $product->variation_color,
                        'productVariationSize' => $product->variation_size,
                        'productItemQty' => $product->qty,
                    ]);

                    $status = true;
                    $message = $product->product->title . ' added in your cart successfully';
                    session()->flash('success', $message);
                } else {
                    $status = false;
                    $message = $product->product->title . ' already added in cart';

                }
            } else {
                // Accessing the 'product' relationship
                Cart::add($product->product->id, $product->product->title, 1, $product->price, [
                    'productItemId' => $product->id,
                    'productSku' => $product->sku,
                    'productImage' => $product->image,
                    'productVariationColor' => $product->variation_color,
                    'productItemQty' => $product->qty,
                ]);

                $status = true;
                $message = $product->product->title . ' added in your cart successfully';
                session()->flash('success', $message);
            }

        } else {
            // Product item not found
            return response()->json([
                'status' => false,
                'message' => 'Product item not found',
                'cartContent' => null,
            ]);
        }

        $cartContentItem = Cart::content()->map(function ($item) {
            $item->sku = $item->options['productSku'] ?? null;
            $item->image = $item->options['productImage'] ?? null;
            $item->variationColor = $item->options['productVariationColor'] ?? null;
            $item->variationSize = $item->options['productVariationSize'] ?? null;
            $item->productItemId = $item->options['productItemId'] ?? null;
            $item->productItemQty = $item->options['productItemQty'] ?? null;
            return $item;
        });

        return response()->json([
            'status' => $status,
            'message' => $message,
            'cartContent' => $cartContentItem
        ]);
    }


    public function cart(){

    $cartContent = (Cart::content());
    $data['cartContent'] = $cartContent;
    //dd($data);
    if(request()->wantsJson()){
        return response()->json([
            "data" =>$cartContent
        ]);
    }
        return view ('front.cart', $data);
    }

    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;
        $itemInfo = Cart::get($rowId);

        $product = Product::find($itemInfo->id);

        //check product item qty
        if($product->has_variation == 1){

            $productItem = ProductItem::with('product')->where('id', $itemInfo->productItemId)->first();

            if ($qty <= $productItem->qty){
                Cart::update($rowId, $qty);
                $message = 'Cart updated successfully';
                $request->session()->flash('success', $message);
                $status = true;
            }
            else {

                $message = 'Request qty('.$qty.') not available in stock';
                $status = false;
                $request->session()->flash('error', $message);
            }
        }else{
            // Check if the product is in stock
            if ($product->track_qty == "Yes" && $qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Cart updated successfully';
                $request->session()->flash('success', $message);
                $status = true;
            } else {
                $message = 'Request qty('.$qty.') not available in stock';
                $status = false;
                $request->session()->flash('error', $message);
            }
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }
    public function deleteItem(Request $request) {
        $itemInfo = Cart::get($request->rowId);

        if ($itemInfo == null) {
            $errorMessage = 'Item not found in cart';
            session()->flash('error', $errorMessage);

            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ]);
        }

        Cart::remove($request->rowId);

        $message = 'Item removed from cart successfully';
        session()->flash('success', $message);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    public function checkout() {
        $discount=0;
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }
        if (Auth::guest()){
            if (!session()->has('url.intended')){
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        session()->forget('url.intended');

        $countries = Country::orderBy('name', 'ASC')->get();
        $subTotal = Cart::subtotal(2,'.','');
         // apply discount here
         if(session()->has('code')){
            $code = session()->get('code');
            if($code->type == 'percent' ){
                $discount = ($code->discount_amount/100)*$subTotal;
            }else{
                $discount = $code->discount_amount;
            }
        }

        //Calculate Shipping here
        if($customerAddress != ''){
            $userCountry = $customerAddress->country_id;
            $shippingInfo = ShippingCharge::where('country_id',$userCountry)->first();

            //echo $shippingInfo->amount;


            $totalShippingCharge = 0;
            $grandTotal = 0;
            $totalQty = 1;
            $totalWeight=0;
            $product_id=[];
            foreach(Cart::content() as $item){
             $product_id[] = $item->id;
            }
            $product = Product::whereIn('id', $product_id)->get();;
            foreach($product as $products){
                  foreach (Cart::content() as $item) {
               $totalQty = $item->qty;
               if($item->id == $products->id){
               $totalWeight +=($products->weight)*$totalQty;
               }
            }
            }

            $weightInfo =  Weight::whereRaw('? BETWEEN min_weight AND max_weight', [$totalWeight])->first();
            if($weightInfo != null){
                $totalShippingCharge = $weightInfo->price+$shippingInfo->amount;
            }else{
                $totalShippingCharge = $shippingInfo->amount;
            }
            $grandTotal = ($subTotal-$discount)+$totalShippingCharge;

        }else{
            $grandTotal = ($subTotal-$discount);
            $totalShippingCharge = 0;
        }

        return view('front.checkout',[
            'countries' => $countries,
            'customerAddress' => $customerAddress,
            'discount' => $discount,
            'totalShippingCharge' =>$totalShippingCharge,
            'grandTotal' => $grandTotal
        ]);
    }
    public function processCheckout(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'errors' => $validator->errors(),
            ]);
        }
        $user  = Auth::user();
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country_id' => $request->country,
            'address' => $request->address,
            'apartment' => $request->apartment,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            ]
        );
        if ($request->payment_method == 'cod'){
            $shipping = 0;
            $discount = 0;
            $discountCodeId='';
            $promoCode='';
            $subTotal = Cart::subtotal(2,'.','');

            // apply discount here

            if(session()->has('code')){
                $code = session()->get('code');
                if($code->type == 'percent' ){
                    $discount = ($code->discount_amount/100)*$subTotal;
                }else{
                    $discount = $code->discount_amount;
                }
                $discountCodeId= $code->id;
                $promoCode =  $code->code;
            }
            // $grandTotal = $subTotal + $shipping + $discount;

            // Calculate Shipping

            $shippingInfo = ShippingCharge::where('country_id',$request->country)->first();

            $totalShippingCharge = 0;
            $grandTotal = 0;
            $totalQty = 1;
            $totalWeight=0;
            $product_id=[];

            foreach(Cart::content() as $item){
             $product_id[] = $item->id;
            }
            $product = Product::whereIn('id', $product_id)->get();;
            foreach($product as $products){
                  foreach (Cart::content() as $item) {
               $totalQty = $item->qty;
               if($item->id == $products->id){
               $totalWeight +=($products->weight)*$totalQty;
               }
            }
            }

            $weightInfo =  Weight::whereRaw('? BETWEEN min_weight AND max_weight', [$totalWeight])->first();
            if($weightInfo != null){
                $totalShippingCharge = $weightInfo->price+$shippingInfo->amount;
            }else{
                $totalShippingCharge = $shippingInfo->amount;
            }

            if($shippingInfo != null){
                $shipping = $totalShippingCharge;
                $grandTotal = ($subTotal-$discount)+ $shipping;

            } else{
                $shippingInfo = ShippingCharge::where('country_id','rest_of_world')->first();
                $shipping = $shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+ $shipping;
            }


            $order = new Order();
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code = $promoCode;
            $order->coupon_code_id = $discountCodeId;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->zip = $request->zip;
            $order->notes = $request->notes;
            $order->country_id = $request->country;
            $order->save();

            foreach (Cart::content() as $key => $item){
                $orderItem = new OrderItem();
                $orderItem->product_id = $item->id;
                $orderItem->product_item_id = $item->productItemId;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->price = $item->price;
                $orderItem->qty = $item->qty;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();

                //send Email
                OrderEmail( $order -> id, 'customer');

                //update product stock
                // $productData = Product::find('$item->id');
                // if($productData->track_qty == 'Yes'){
                //     $currentQty = $productData->qty;
                //     $updatedQty = $currentQty-$item->qty;
                //     $productData->qty = $updatedQty;
                //     $productData->save();
                // }
                $productData = Product::find($item->id);
                    if ($productData) { // Check if the product was found
                        if ($productData->track_qty == 'Yes') {
                            $currentQty = $productData->qty;
                            $updatedQty = $currentQty - $item->qty;
                            $productData->qty = $updatedQty;
                            // Wrap the save operation in a try-catch block to handle potential errors
                            try {
                                $productData->save();
                            } catch (\Exception $e) {
                                // Handle the error, e.g., log it or return a response
                                // You can access the error message with $e->getMessage()
                            }
                        } else {
                            // Handle the case where track_qty is not 'Yes'
                        }
                    } else {
                        // Handle the case where the product with the given $item->id was not found
                    }
            }
            session()->flash('success', 'You Have Successfully placed your order');

            Cart::destroy();
            session()->forget('code');

            return response()->json([
                'status' => true,
                'message' => 'Order Saved successfully',
                'orderId' => $order->id,
            ]);

        }else{
            //
        }
        return response()->json([
            'status' => true,
            'message' => 'Checkout successful',
        ]);
    }

    public function thankyou($id){
        return view('front.thank',[
            'id' =>$id
        ]);
    }

    public function getOrderSummery(Request $request){

        $subTotal = Cart::subTotal(2,'.','');
        $discount=0;
        $discountString='';

        // apply discount here
        if(session()->has('code')){
            $code = session()->get('code');
            if($code->type == 'percent' ){
                $discount = ($code->discount_amount/100)*$subTotal;
            }else{
                $discount = $code->discount_amount;
            }
            $discountString =  '<div class=" mt-4" id="discount-response" >
                                <strong>'.session()->get('code')->code.'</strong>
                                <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times">
                                </i></a>
                            </div>';
        }


        if ($request->country_id > 0){

            $shippingInfo = ShippingCharge::where('country_id',$request->country_id)->first();

            $totalShippingCharge = 0;
            $grandTotal = 0;
            $totalQty = 1;
            $totalWeight=0;
            $product_id=[];
            foreach(Cart::content() as $item){
             $product_id[] = $item->id;
            }
            $product = Product::whereIn('id', $product_id)->get();;
            foreach($product as $products){
                  foreach (Cart::content() as $item) {
               $totalQty = $item->qty;
               if($item->id == $products->id){
               $totalWeight +=($products->weight)*$totalQty;
               }
            }
            }

            $weightInfo =  Weight::whereRaw('? BETWEEN min_weight AND max_weight', [$totalWeight])->first();
            if($weightInfo != null){
                $totalShippingCharge = $weightInfo->price+$shippingInfo->amount;
            }else{
                $totalShippingCharge = $shippingInfo->amount;
            }

            if($shippingInfo != null){
                $shipping = $totalShippingCharge;
                $grandTotal =($subTotal - $discount)+ $shipping;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shipping' => number_format($shipping,2),
                ]);

            } else{
            $shippingInfo = ShippingCharge::where('country_id','rest_of_world')->first();
            $shipping = $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount)+ $shipping;

                return response()->json([
                    'status' => true,
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'grandTotal' => number_format($grandTotal,2),
                    'shipping' => number_format($shipping,2),
                ]);

            }
        }else {
            return response()->json([
                'status' => true,
                'grandTotal' => number_format(($subTotal - $discount),2),
                'discount' => number_format($discount,2),
                'discountString' => $discountString,
                'shipping' => number_format(0,2),
            ]);

        }

    }
    public function applyDiscount( Request $request){
        $code = DiscountCoupon:: where('code', $request->code)->first();


        if($code == null){
            return response()->json([
                        'status'=>false,
                        'message'=>'Invalid discount coupon'
                    ]);
        }
        //check if coupon start date is calid or not
        $now = Carbon:: now();
        if($code->starts_at!=""){
        $startDate= Carbon::createFromFormat('Y-m-d H:i:s' , $code->starts_at);

        if($now ->lt($startDate)){
            return response()->json([
                        'status'=>false,
                        'message'=>'Invalid discount coupon1'
                    ]);
        }
        }
        if($code->expires_at!=""){
        $endDate= Carbon::createFromFormat('Y-m-d H:i:s' , $code->expires_at);

        if($now ->gt($endDate)){
            return response()->json([
                        'status'=>false,
                        'message'=>'Invalid discount coupon2'
                    ]);
        }
        }

        // Max uses check
        if($code->max_uses > 0){
            $couponUsed = Order::where('coupon_code_id',$code->id)->count();
            if($couponUsed >= $code->max_uses){
                return response()->json([
                    'status'=>false,
                    'message'=>'max uses reached'
                ]);
            }
        }


         // Max user uses check
         if($code->Max_uses_user > 0){
            $couponUsedByUser = Order::where(['coupon_code_id'=> $code->id, 'user_id'=>Auth::user()->id])->count();
            if($couponUsedByUser >= $code->Max_uses_user){
                return response()->json([
                    'status'=>false,
                    'message'=>'You already used this coupon'
                ]);
            }

         }

         //min amount condition check
         $subTotal = Cart::subtotal(2,'.','');
         if($code->min_amount >0){
            if($subTotal < $code->min_amount){
                return response()->json([
                    'status'=>false,
                    'message'=>'You minimum Amount must be ৳'.$code->min_amount.'.'
                ]);
            }

         }


        session()->put('code',$code);
        return $this->getOrderSummery($request);
    }

    public function removeCoupon(Request $request){
        session()->forget('code');
        return $this->getOrderSummery($request);

    }


  }
