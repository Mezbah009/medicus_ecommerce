<?php

namespace App\Http\Controllers;

use App\Models\ProductItem;
use App\Models\Slider;
use App\Models\Wishlist;
use App\Mail\ContactEmail;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FrontController extends Controller
{
    public function index()
    {

        $sliders = Slider::where('active', 'Yes')
            ->orderBy('id', 'desc')->take(3)
            ->get();
        $data['slider'] = $sliders;

        $products = Product::where('is_featured', 'Yes')
            ->orderBy('id', 'DESC')->take(10)
            ->where('status', 1)
            ->with('product_items')
            ->get();

        $data['featuredProducts'] = $products;

        $latestProducts = Product::orderBY('id', 'DESC')
            ->where('status', 1)
            ->with('product_items')
            ->take(10)
            ->get();

        $data['latestProducts'] = $latestProducts;

        return view('front.home', $data);
    }
    public function page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if ($page == null) {
            abort(404);
        }
        return view('front.page', compact('page'));
    }


    public function addToWishlist(Request $request)
    {

        if (Auth::check() == false) {

            session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false
            ]);
        }

        $product = Product::where('id', $request->id)->first();
        if ($product == null) {
            return response()->json([
                'status' => true,
                'massage' => '<div class="alert alert-danger">Product not found.</div>'
            ]);
        }
        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
                'product_item_id' => NULL,

            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
                'product_item_id' => NULL,
            ]
        );
        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"' . $product->title . '"</strong> added in your wishlist </div>'
        ]);
    }

    public function addToItemWishlist(Request $request)
    {

        if (Auth::check() == false) {

            session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false
            ]);
        }
        $productItem = ProductItem::where('id', $request->id)->with('product')->first();
        //dd($productItem);
        if ($productItem == null) {
            return response()->json([
                'status' => true,
                'massage' => '<div class="alert alert-danger">Product not found.</div>'
            ]);
        }
        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $productItem->product_id,
                'product_item_id' => $request->id,

            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $productItem->product_id,
                'product_item_id' => $request->id,
            ]
        );
        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"' . $productItem->product->title . '"</strong> added in your wishlist </div>'
        ]);
    }

    public function sendContactEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',

        ]);
        if ($validator->passes()) {
            //send mail
            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'mail_subject' => 'You have send a email From contact'
            ];
            $admin = User::where('id', 1)->first();

            Mail::to($admin->email)->send(new ContactEmail($mailData));
            session()->flash('success', 'Thanks for contacting us, We will contact with you soon ');

            return response()->json([
                'status'  =>  true

            ]);
        } else {
            return response()->json([
                'status'  =>  false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
