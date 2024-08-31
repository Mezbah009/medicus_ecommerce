<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use PDF;
use Illuminate\Support\Facades\View;


class OrderController extends Controller
{
    public function index(Request $request){
        $orders = Order::latest('orders.created_at')->select('orders.*','users.name','users.email');
        $orders = $orders->leftJoin('users','users.id','orders.user_id');
        if($request->get('keyword')!= ""){
            $orders = $orders->where('users.name','like','%'.$request->keyword.'%');
            $orders = $orders->orWhere('users.email','like','%'.$request->keyword.'%');
            $orders = $orders->orWhere('orders.id','like','%'.$request->keyword.'%');
        }
        $orders = $orders->paginate(10);
        return view('admin.orders.list', [
            'orders' => $orders]);
    }

    public function detail($orderId){
        $order = Order::select('orders.*','countries.name as countryName')
        ->where('orders.id', $orderId)
        ->leftJoin('countries','countries.id','orders.country_id')
        ->first();

        $orderItems = OrderItem::where('order_id',$orderId)
            ->with('product_item')
            ->get();
        //dd($orderItems);
        return view('admin.orders.detail', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }

    public function downloadPdf($id) {
        // Load the order and related data
        $order = Order::select('orders.*', 'countries.name as countryName')
            ->where('orders.id', $id)
            ->leftJoin('countries', 'countries.id', 'orders.country_id')
            ->first();

        $orderItems = OrderItem::where('order_id', $id)
            ->with('product_item')
            ->get();

        // Generate the PDF view
        $pdf = PDF::loadView('admin.orders.pdf', [
            'order' => $order,
            'orderItems' => $orderItems,
        ]);

        //dd($orderItems);
        // Generate a unique file name with the current timestamp
    $fileName = 'order_details_' . now()->format('Y-m-d_His') . '.pdf';

    return $pdf->download($fileName);
    }


    public function changeOrderStatus(Request $request, $orderId){
       $order = Order::find($orderId);
       //dd($order);
       $order->status = $request->status;
       $order->shipped_date = $request->shipped_date;
       $order->save();

       $message = 'Order status updated successfully';

       session()->flash('success',$message);

       return response()->json([
        'status' => true,
        'message' => $message
       ]);
    }

    public function sendInvoiceEmail(Request $request, $orderId){
        OrderEmail($orderId, $request->userType);

        $message = 'Order email sent successfully';

        session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
