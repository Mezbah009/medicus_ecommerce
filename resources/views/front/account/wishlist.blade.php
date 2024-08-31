@extends('front.layouts.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-gray">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                <li class="breadcrumb-item">My Wishlist</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div >
                @include('front.account.common.message')
            </div>
            <div class="col-md-3">
                @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">My Wishlist</h2>
                    </div>

                    <div class="card-body p-4">
                        @if ($wishlists->isNotEmpty())
                        @foreach ($wishlists as $wishlist)
                        <div class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom">
                            <div class="d-block d-sm-flex align-items-start text-center text-sm-start">

                                    {{-- <img src="images/product-1.jpg" alt="Product"> --}}
                                @php
                                    $productImage = getProductImage ($wishlist->product_id);
                                @endphp
                                @if($wishlist->product_items != null)
                                    <a class="d-block flex-shrink-0 mx-auto me-sm-4" href="{{ route("front.product", $wishlist->product->slug) }}" style="width: 10rem;">
                                        @if (!empty($wishlist->product_items->image))
                                            <img class="card-img-top" src="{{ asset('uploads/product/item/'.$wishlist->product_items->image) }}" class="img-thumbnail" alt="{{ $wishlist->product->title }}" height="150px" width="150px" />
                                        @else
                                            <img src="{{ asset('admin-assets/img/default.png') }}"  alt="default image" height="150px" width="150px" />
                                        @endif
                                    </a>
                                @else
                                    <a class="d-block flex-shrink-0 mx-auto me-sm-4" href="{{ route("front.product", $wishlist->product->slug) }}" style="width: 10rem;">
                                        @if (!empty($productImage))
                                            <img class="card-img-top" src="{{ asset('uploads/product/small/'.$productImage->image) }}" class="img-thumbnail" alt="{{ $wishlist->product->title }}" height="150px" width="150px" />
                                        @else
                                            <img src="{{ asset('admin-assets/img/default.png') }}"  alt="default image" height="150px" width="150px" />
                                        @endif
                                    </a>
                                @endif

                                <div class="pt-2">
                                    <h3 class="product-title fs-base mb-2"><a href="{{ route("front.product", $wishlist->product->slug) }}">{{ $wishlist->product->title }}</a>
                                        @if($wishlist->product_items != null)
                                            @php
                                                $decodedColor = !empty($wishlist->product_items->variation_color) ? json_decode($wishlist->product_items->variation_color, true) : null;
                                                $decodedSize = !empty($wishlist->product_items->variation_size) ? json_decode($wishlist->product_items->variation_size, true) : null;
                                            @endphp
                                            @if (!empty($decodedColor[0]['name']))
                                                <p class="color-text">Color: {{$decodedColor[0]['name']}},</p>
                                            @endif

                                            @if (!empty($decodedSize[0]['name']))
                                                <p class="size-text">Size: {{$decodedSize[0]['name']}}</p>
                                            @endif
                                        @endif
                                    </h3>
                                    @if($wishlist->product_items != null)
                                        <div class="fs-lg text-accent pt-2">
                                            <span class="h5"><strong>৳{{ $wishlist->product_items->price }}</strong></span>
                                            @if ($wishlist->product_items->compare_price > 0)
                                                <span class="h6 text-underline"><del>৳{{ $wishlist->product_items->compare_price }}</del></span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="fs-lg text-accent pt-2">
                                            <span class="h5"><strong>৳{{ $wishlist->product->price }}</strong></span>
                                            @if ($wishlist->product->compare_price > 0)
                                                <span class="h6 text-underline"><del>৳{{ $wishlist->product->compare_price }}</del></span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                <button onclick="removeProduct({{ $wishlist->product_id }});" class="btn btn-outline-danger btn-sm" type="button"><i class="fas fa-trash-alt me-2"></i>Remove</button>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div>
                            <h3 class="h5">Your wishlist is Empty!!</h3>
                        </div>


                        @endif

                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@section('customJs')
<script>
    function removeProduct(id){
        $.ajax({
                url: '{{route("account.removeProductFromWishList")}}',
                type: 'post',
                data: {id:id}, // You can include data to send to the server if needed
                dataType: 'json',
                success: function (response) {
                    if (response.status == true){
                        window.location.href = "{{ route('account.wishlist') }}";
                    }

                }
            });

    }
</script>

@endsection
