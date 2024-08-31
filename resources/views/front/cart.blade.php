@php
    $discount=0;
@endphp
@extends('front.layouts.app')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-gray">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.shop')}}">Shop</a></li>
                    <li class="breadcrumb-item">Cart</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-9 pt-4">
        <div class="container">
            <div class="row">
                @if(Session::has('success'))
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
                @endif
                    @if(Session::has('error'))
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ Session::get('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    {{--@if(!empty($cartContent))--}}
                    @if(Cart::count() > 0)
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table" id="cart">
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                                </thead>
                                <tbody>

{{--                                @dd($cartContent)--}}
                                @foreach($cartContent as $item)
                                        <tr>
                                        <td class="text-start cart-content">
                                            <div class="d-flex align-items-center justify-content-start">
                                                <img src="" width="" height="">
                                                @if(!empty($item->options->productImage->image))
                                                    <img  src="{{asset('uploads/product/small/'.$item->options->productImage->image)}}" width="" height="">
                                                @elseif(!empty($item->image))
                                                    <img  src="{{asset('uploads/product/item/'.$item->image)}}" width="" height="">
                                                @else
                                                    <img src="{{asset('admin-assets/img/default.png/')}}" width="" height="">
                                                @endif
                                                <h2>{{$item->name}}</h2>
                                                @if(!empty($item->variationColor) || !empty($item->variationSize))
                                                    @php
                                                        $decodedColor = !empty($item->variationColor) ? json_decode($item->variationColor, true) : null;
                                                        $decodedSize = !empty($item->variationSize) ? json_decode($item->variationSize, true) : null;
                                                    @endphp

                                                    @if (!empty($decodedColor[0]['name']))
                                                        <p>Color: {{$decodedColor[0]['name']}},</p>
                                                    @endif

                                                    @if (!empty($decodedSize[0]['name']))
                                                        <p>Size: {{$decodedSize[0]['name']}}</p>
                                                    @endif
                                                @endif


                                            </div>
                                        </td>
                                        <td>৳{{$item->price}}</td>
                                        <td>
                                            @if(isset($item->productItemQty))
                                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub" data-id="{{$item->rowId}}">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm  border-0 text-center" value="{{ $item->qty }}" >
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add" data-id="{{$item->rowId}}">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub" data-id="{{$item->rowId}}">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm  border-0 text-center" value="{{ $item->qty }}" >
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add" data-id="{{$item->rowId}}">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>৳{{ $item->price * $item->qty }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" onclick="deleteItem('{{$item->rowId}}')"><i class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card cart-summery">

                            <div class="card-body">
                                <div class="sub-title">
                                    <h2 class="bg-white">Cart Summery</h2>
                                </div>
                                <div class="d-flex justify-content-between pb-2">
                                    <div>Subtotal</div>
                                    <div>৳{{Cart::subTotal()}}</div>
                                </div>
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Discount</strong></div>
                                    <div class="h6"><strong id="discount_value" name="discount_value" >৳{{$discount }}</strong></div>
                                </div>

                                <div class="pt-2">
                                    <a href="{{route('front.checkout')}}" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                                </div>
                            </div>
                        </div>
                        <div class="input-group apply-coupan mt-4">
                            <input type="text" placeholder="Coupon Code" class="form-control" name="discount_code" id="discount_code">
                            <button class="btn btn-dark" type="button" id="apply-discount">Apply Coupon</button>
                        </div>
                        <div id="discount-response-wrapper">
                            @if(Session::has('code'))
                                <div class=" mt-4" id="discount-response" >
                                    <strong>{{Session::get('code')->code}}</strong>
                                    <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times">
                                    </i></a>
                                </div>

                            @endif
                    </div>
                    @else
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <h3>Your Cart is Empty!</h3>
                                </div>
                            </div>
                        </div>
                    @endif
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script>
        $('.add').click(function(){
            var qtyElement = $(this).parent().prev(); // Qty Input
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue < 10) {
                qtyElement.val(qtyValue+1);

                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId,newQty);
            }
        });

        $('.sub').click(function(){
            var qtyElement = $(this).parent().next();
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue > 1) {
                qtyElement.val(qtyValue-1);

                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId,newQty);
            }
        });

        function updateCart(rowId,qty){
            $.ajax({
                url: '{{route("front.updateCart")}}',
                type: 'post',
                data: {rowId:rowId, qty:qty},
                dataType: 'json',
                success: function(response){
                    window.location.href = '{{route("front.cart")}}'
                }
            });
        }

        function deleteItem(rowId) {
            if (confirm("Are you sure you want to delete?")) {
                $.ajax({
                    url: '{{ route("front.deleteItem.cart") }}',
                    type: 'post',
                    data: {rowId: rowId},
                    dataType: 'json',
                    success: function (response) {
                        window.location.href = '{{ route("front.cart") }}';
                    },
                });
            }
        }

        $("#apply-discount").click(function () {
    $.ajax({
        url: '{{ route("front.applyDiscount") }}',
        type: 'post',
        data: {
            code: $("#discount_code").val(),
            country_id: $("#country").val()
        },
        dataType: 'json',
        success: function (response) {
            if (response.status == true) {
                   $("#shippingAmount").html('৳'+response.shipping);
                    $("#grandTotal").html('৳'+response.grandTotal);
                    $("#discount_value").html('-৳'+response.discount);
                    $("#discount-response-wrapper").html(response.discountString);
            }else{
                $("#discount-response-wrapper").html("<span class='text-danger'>"+response.message+"</span>");

            }
        }
    });
});

$('body').on('click',"#remove-discount",function(){
    $.ajax({
        url: '{{ route("front.removeCoupon") }}',
        type: 'post',
        data: {
            country_id: $("#country").val()
        },
        dataType: 'json',
        success: function (response) {
            if (response.status == true) {
                   $("#shippingAmount").html('৳'+response.shipping);
                    $("#grandTotal").html('৳'+response.grandTotal);
                    $("#discount_value").html('-৳'+response.discount);
                    $("#discount-response").html('');
                    $("#discount_code").val('');


            }
        }
    });
});
    </script>
@endsection
