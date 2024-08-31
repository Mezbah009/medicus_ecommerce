@extends('front.layouts.app')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-gray">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('account.profile')}}">My Account</a></li>
                    <li class="breadcrumb-item">My Orders</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div >
                    @include('admin.message')
                </div>
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">

                    <div class="card">
                        <form action="" name="profileForm" id="profileForm">
                            <div class="card-header">
                                <h2 class="h5 mb-0 pt-2 pb-2">My Order</h2>
                            </div>
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Order Id</th>
                                            <th>Date Purchased</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Order Details</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($orders->isNotEmpty())
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td>
                                                        <a href="{{route('account.orderDetails',$order->id)}}">{{$order->id}}</a>
                                                    </td>
                                                    <td>{{ \Carbon\carbon::parse($order->created_at)->format('d M,Y') }}</td>
                                                    <td>
                                                        @if($order->status == 'pending')
                                                            <span class="badge bg-warning">pending</span>
                                                        @elseif($order->status == 'shipped')
                                                            <span class="badge bg-info">Shipped</span>
                                                        @elseif($order->status == 'delivered')
                                                            <span class="badge bg-success">Delivered</span>
                                                        @else
                                                            <span class="badge bg-danger">Cancelled</span>    
                                                        @endif

                                                    </td>
                                                    <td>৳{{ number_format($order->grand_total,2) }}</td>
                                                    <td>     
                                                    <a href="{{route('account.orderDetails',$order->id)}}">                                
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                                                                <path d="M288 80c-65.2 0-118.8 29.6-159.9 67.7C89.6 183.5 63 226 49.4 256c13.6 30 40.2 72.5 78.6 108.3C169.2 402.4 222.8 432 288 432s118.8-29.6 159.9-67.7C486.4 328.5 513 286 526.6 256c-13.6-30-40.2-72.5-78.6-108.3C406.8 109.6 353.2 80 288 80zM95.4 112.6C142.5 68.8 207.2 32 288 32s145.5 36.8 192.6 80.6c46.8 43.5 78.1 95.4 93 131.1c3.3 7.9 3.3 16.7 0 24.6c-14.9 35.7-46.2 87.7-93 131.1C433.5 443.2 368.8 480 288 480s-145.5-36.8-192.6-80.6C48.6 356 17.3 304 2.5 268.3c-3.3-7.9-3.3-16.7 0-24.6C17.3 208 48.6 156 95.4 112.6zM288 336c44.2 0 80-35.8 80-80s-35.8-80-80-80c-.7 0-1.3 0-2 0c1.3 5.1 2 10.5 2 16c0 35.3-28.7 64-64 64c-5.5 0-10.9-.7-16-2c0 .7 0 1.3 0 2c0 44.2 35.8 80 80 80zm0-208a128 128 0 1 1 0 256 128 128 0 1 1 0-256z"/>
                                                            </svg>
                                                    </a>
                                                    <a href="{{route('front.account.pdf', $order->id)}}" >
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 20 20">
                                                            <path d="M16.895,0H3.105A3.108,3.108,0,0,0,0,3.105V16.895A3.108,3.108,0,0,0,3.105,20H16.895A3.108,3.108,0,0,0,20,16.895V3.105A3.108,3.108,0,0,0,16.895,0ZM15.385,5.692h-5.6V2.154L15.385,5.692ZM5.077,15.846V13.231h1.692V7.077H5.077V5.231h6.923v1.846H9.308V13.231h1.692v2.615ZM15.385,5.692h-5.6V2.154L15.385,5.692Z" />
                                                        </svg>
                                                    </a>
                                                    
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">Orders not Found</td>
                                            </tr>
                                        @endif


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </section>
@endsection
