@extends('front.layouts.app')
@section('content')
    <style>
        /* Style for the color swatches */
        .color-swatch {
            border-radius: 50%;
            display: inline-block;
            width: 33px;
            height: 33px;
            margin: 2px;
            cursor: pointer;
            border: 1px solid #ccc;
        }
        .select-any-color, .select-any-size {
            display: none;
        }

        .size-swatch {
            display: inline-block;
            width: 33px;
            height: 33px;
            margin: 2px;
            cursor: pointer;
            border: 1px solid #ccc;
        }
        .size-swatch p {
            text-align: -webkit-center;
            padding: 3px 0px;
        }
        /* Style for the selected swatch */
        .selected-swatch {
            border: 2px solid #333;
        }
        .color-swatch.selected {
            border: 2px solid #000; /* Set the border style as needed */
        }
        .size-swatch.selected {
            border: 2px solid #000; /* Set the border style as needed */
        }

        /* Style for the product image */
        .product-image {
            width: 200px;
            height: 200px;
        }
    </style>
    <section class="section-5 pt-3 pb-3 mb-3 bg-gray">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{  route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{  route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">{{ $product->title }}</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="section-7 pt-3 mb-3">
        <div class="container">
            <div class="row ">
                @include('front.account.common.message')
                @if($product->product_items->isNotEmpty())
                    <div class="col-md-5">
                        <div id="product-carouse-two" class="carousel slide">
                            <div class="carousel-inner bg-light">
                                @foreach ($product->product_items as $key => $productItem)
                                    <div class="carousel-item {{ ($key == 0) ? 'active' : '' }}">
                                        <img class="product-image w-100 h-100" id="productImage" src="{{ asset('uploads/product/item/' . $productItem->image) }}" alt="Product Image">
                                    </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#product-carouse-two" data-bs-slide="prev">
                                <i class="fa fa-2x fa-angle-left text-dark"></i>
                            </a>
                            <a class="carousel-control-next" href="#product-carouse-two" data-bs-slide="next">
                                <i class="fa fa-2x fa-angle-right text-dark"></i>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="col-md-5">
                        <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner bg-light">
                                @if ($product->product_images)
                                    @foreach ($product->product_images as $key => $productImage)
                                        <div class="carousel-item {{ ($key == 0) ? 'active' : '' }}">
                                            <img class="w-100 h-100" src="{{ asset('uploads/product/large/' . $productImage->image) }}" alt="Image">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                                <i class="fa fa-2x fa-angle-left text-dark"></i>
                            </a>
                            <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                                <i class="fa fa-2x fa-angle-right text-dark"></i>
                            </a>
                        </div>
                    </div>

                @endif
                <div class="col-md-7">
                    <div class="bg-white right">
                        <h1>{{ $product->title }}</h1>
                        @if($product->brand != null)
                            <p>Brand By {{ optional($product->brand)->name }}</p>
                        @endif
                        <div class="star-rating product mt-2" title="">
                            <div class="back-stars">
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>

                                <div class="front-stars" style="width: {{ $avgRatingPer }}%">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <small class="pt-2 ps-1">({{ ($product->product_ratings_count > 1) ?
                            $product->product_ratings_count.' Reviews' :
                            $product->product_ratings_count.' Review' }})</small>
                    </div>
                    @if ($product->compare_price != Null)
                        <h2 class="price-compare text-danger "><del>৳{{ $product->compare_price }}</del></h2>
                    @endif
                    @if($product->price != 0)
                        <h2 class="price ">৳{{ $product->price }}</h2>
                    @endif
                    @if ($product->product_items->isNotEmpty() && $product->has_variation == 1)
                        @if ($product->product_items[0]->compare_price != null)
                            <h2 class="priceItem text-secondary text-secondary-item" id="comparePrice_{{ $product->product_items[0]->id }}"><del>৳{{ $product->product_items[0]->compare_price }}</del></h2>
                        @endif
                        <h2 class="priceItem" id="priceItem_{{ $product->product_items[0]->id }}">৳{{ $product->product_items[0]->price }}</h2>

                    @endif

                    @if (!empty($variationData))
                        @if($hasColors == true)
                            <p id="select-any-color" class="select-any-color">please select any color</p>
                        @endif
                        <div class="col-md-2">
                            <div id="color-swatches">
                                @php
                                    $uniqueColors = [];
                                @endphp
                                @foreach ($variationData as $key => $productItem)

                                    {{-- Collect unique sizes --}}
                                    @if($productItem['color'] != Null)
                                        @foreach(array_unique($productItem['color'], SORT_REGULAR) as $color)
                                            @if (!in_array($color->id, $uniqueColors))
                                                @php
                                                    $uniqueColors[] = $color->id;
                                                @endphp
                                                <div class="color-swatch" style="background-color: {{ $color->code }}"
                                                     data-color-id="{{ $color->id }}"
                                                     data-product-id="{{ $productItem["product-item-id"] }}"
                                                     data-price="{{ $productItem['price'] }}"
                                                     data-compare-price="{{ $productItem['compare'] }}"
                                                     data-image="{{ $productItem['image'] }}"
                                                ></div>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @if($hasSizes == true)
                        <p id="select-any-size" class="select-any-size">please select any size</p>
                        @endif
                        <div class="col-md-2">
                            <div id="size-swatches">
                                @php
                                    $uniqueSizes = [];
                                @endphp
                                @foreach ($variationData as $key => $productItem)
                                    {{-- Collect unique sizes --}}
                                    @if($productItem['size'] != Null)
                                        @foreach(array_unique($productItem['size'], SORT_REGULAR) as $size)
                                            @if (!in_array($size->id, $uniqueSizes))
                                                @php
                                                    $uniqueSizes[] = $size->id;
                                                @endphp
                                                <div class="size-swatch" style="background-color: {{ $size->code }}"
                                                     data-size-id="{{ $size->id }}"
                                                     data-product-id="{{ $productItem["product-item-id"] }}"
                                                     data-price="{{ $productItem['price'] }}"
                                                     data-compare-price="{{ $productItem['compare'] }}"
                                                     data-image="{{ $productItem['image'] }}"

                                                ><p>{{ $size->name }}</p></div>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    @endif
                    {!! $product->short_description !!}
                    {{-- <a href="javascript:void(0)" onclick="addToCart({{$product->id}})" class="btn btn-dark"><i class="fas fa-shopping-cart"></i> &nbsp;ADD TO CART</a> --}}
                    @if($product->track_qty == 'Yes')
                        @if ($product->qty > 0)
                            @if($product->has_variation == 1 )
                                <button id="addToCartBtn" class="btn btn-dark">
                                    <i class="fas fa-shopping-cart"></i> &nbsp; Add To Cart
                                </button>
                            @else
                                <a href="javascript:void(0)" onclick="addToCart({{$product->id}})" class="btn btn-dark">
                                    <i class="fas fa-shopping-cart"></i>  Add To Cart
                                </a>
                            @endif
                        @else
                            <a class="btn btn-dark" href="javascript:void(0)" >Out Of Stock
                            </a>
                        @endif
                    @else
                        <a href="javascript:void(0)" onclick="addToCart({{$product->id}})" class="btn btn-dark">
                            <i class="fas fa-shopping-cart"></i> &nbsp; Add To Cart
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-12 mt-5">
                <div class="bg-light">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping & Returns</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            {!! $product->description !!}
                        </div>
                        <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                            {!! $product->shipping_returns !!}
                        </div>
                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                            <div class="col-md-8">
                                <div class="row">

                                    <form action="" name="productRatingForm" id="productRatingForm" method="post">

                                        <h3 class="h4 pb-3">Write a Review</h3>
                                        <div class="form-group col-md-6 mb-3">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Name">
                                            <p></p>
                                        </div>
                                        <div class="form-group col-md-6 mb-3">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                                            <p></p>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="rating">Rating</label>
                                            <br>
                                            <div class="rating" style="width: 10rem">
                                                <input id="rating-5" type="radio" name="rating" value="5"/><label for="rating-5"><i class="fas fa-3x fa-star"></i></label>
                                                <input id="rating-4" type="radio" name="rating" value="4"  /><label for="rating-4"><i class="fas fa-3x fa-star"></i></label>
                                                <input id="rating-3" type="radio" name="rating" value="3"/><label for="rating-3"><i class="fas fa-3x fa-star"></i></label>
                                                <input id="rating-2" type="radio" name="rating" value="2"/><label for="rating-2"><i class="fas fa-3x fa-star"></i></label>
                                                <input id="rating-1" type="radio" name="rating" value="1"/><label for="rating-1"><i class="fas fa-3x fa-star"></i></label>
                                            </div>
                                            <p class="product-rating-error text-danger"></p>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="">How was your overall experience?</label>
                                            <textarea name="comment"  id="comment" class="form-control" cols="30" rows="10" placeholder="How was your overall experience?"></textarea>
                                            <p></p>
                                        </div>
                                        <div>
                                            <button class="btn btn-dark">Submit</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <div class="col-md-12 mt-5">
                                <div class="overall-rating mb-3">
                                    <div class="d-flex">
                                        <h1 class="h3 pe-3">{{ $avgRating }}</h1>
                                        <div class="star-rating mt-2" title="">
                                            <div class="back-stars">
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>

                                                <div class="front-stars" style="width: {{ $avgRatingPer }}%">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pt-2 ps-2">({{ ($product->product_ratings_count > 1) ?
                                            $product->product_ratings_count.' Reviews' :
                                            $product->product_ratings_count.' Review' }}) </div>
                                    </div>

                                </div>

                                @if ($product->product_ratings->isNotEmpty())
                                    @foreach ($product->product_ratings as $rating)

                                        @php
                                            $ratingPer = ($rating->rating*100)/5;
                                        @endphp
                                        <div class="rating-group mb-4">
                                            <span> <strong>{{ $rating->username }} </strong></span>
                                            <div class="star-rating mt-2" title="">
                                                <div class="back-stars">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>

                                                    <div class="front-stars" style="width: {{ $ratingPer }}%">
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="my-3">
                                                <p>{{  $rating->comment }}</p>
                                            </div>
                                        </div>
                                    @endforeach

                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    @if(!empty($relatedProducts))
        <section class="pt-5 section-8">
            <div class="container">
                <div class="section-title">
                    <h2>Related Products</h2>
                </div>
                <div class="col-md-12">
                    <div id="related-products" class="carousel">

                        @foreach ( $relatedProducts as $relProduct)
                            @php
                                $productImage = $relProduct->product_images->first();
                            @endphp

                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="" class="product-img">
                                        {{-- <img class="card-img-top" src="images/product-1.jpg" alt=""> --}}
                                        @if (!empty($productImage->image))
                                            <img class="card-img-top" src="{{ asset('uploads/product/small/' . $productImage->image) }}" class="img-thumbnail" alt="{{ $relProduct->title }}" height="150px" width="150px" />
                                        @else
                                            <img src="{{ asset('admin-assets/img/default.png') }}"  alt="default image" height="150px" width="150px" />
                                        @endif

                                    </a>
                                    <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                    <div class="product-action">
                                        {{-- <a class="btn btn-dark" href="#">
                                            <i class="fa fa-shopping-cart"></i> Add To Cart
                                        </a> --}}

                                        @if($relProduct->track_qty == 'Yes')
                                            @if ($relProduct->qty > 0)
                                                <a href="javascript:void(0)" onclick="addToCart({{$relProduct->id}})" class="btn btn-dark">
                                                    <i class="fas fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0)" >
                                                    Out Of Stock
                                                </a>
                                            @endif
                                        @else
                                            <a href="javascript:void(0)" onclick="addToCart({{$relProduct->id}})" class="btn btn-dark">
                                                <i class="fas fa-shopping-cart"></i> Add To Cart
                                            </a>
                                        @endif

                                    </div>
                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="">{{ $relProduct->title }}</a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>৳{{ $relProduct->price }}</strong></span>
                                        @if ($relProduct->compare_price > 0)
                                            <span class="h6 text-underline"><del>৳{{ $relProduct->compare_price }}</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
@section('customJs')
    <script type="text/javascript">

        $("#productRatingForm").submit(function(event){
            event.preventDefault();
            $.ajax({
                url :'{{ route("front.saveRating",$product->id) }}',
                type:'post',
                data:$(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    // Handle success response here
                    $("button[type=submit]").prop('disabled',false);
                    if(response["status"] == true){
                        window.location.href="{{ route('front.product', $product->slug) }}";

                    }else{
                        var errors = response['errors'];
                        $(".error").removeClass('is-invalid').html('');
                        $("input[type='text'], select").removeClass('is-invalid');
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid'); // Add the 'is-invalid' class to the input
                            $(`#${key}`).next('p').addClass('invalid-feedback').html(value); // Add the error message
                        });

                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong");
                }
            });
        });

        $(document).ready(function () {
            // Check if there are any product items
            var productItems = {!! $product->product_items->isNotEmpty() ? json_encode($product->product_items) : '[]' !!};

            if (productItems.length > 0) {
                // Get the initial price, compare price, and image from the first color and size swatch
                var initialPrice = productItems[0].price;
                var initialComparePrice = productItems[0].compare_price;
                var initialImage = productItems[0].image;

                // Set the initial prices and image
                updatePrices(initialPrice, initialComparePrice, initialImage);

                // Handle click event on color and size swatches
                $('.color-swatch').on('click', function () {
                    var comparePrice = $(this).data('compare-price');
                    var price = $(this).data('price');
                    var image = $(this).data('image');

                    // Update displayed prices and image
                    updatePrices(price, comparePrice, image);
                });
            }

            // Function to update displayed prices and image
            function updatePrices(price, comparePrice, image) {
                // Update displayed regular price
                $('#priceItem_' + productItems[0].id).html('৳' + price);

                // Update displayed compare prices if available
                if (comparePrice !== null) {
                    $('#comparePrice_' + productItems[0].id).html('<del>৳' + comparePrice + '</del>').show();
                } else {
                    $('#comparePrice_' + productItems[0].id).hide();
                }

                // Update product image
                $('#productImage').attr('src', '{{ asset('uploads/product/item/') }}' + '/' + image); // Adjust the image path accordingly
            }
        });

        $(document).ready(function () {
            var variationData = <?php echo isset($variationData) ? json_encode($variationData) : 'null'; ?>;
            var hasSizes = <?php echo isset($hasSizes) ? json_encode($hasSizes) : 'null'; ?>;
            var hasColors = <?php echo isset($hasColors) ? json_encode($hasColors) : 'null'; ?>;
            console.log(variationData)

            var selectedColor = null;
            var selectedSize = null;

            function updateAddToCartButton() {
                if (hasSizes && hasColors) {
                    $('#addToCartBtn').prop('disabled', !(selectedColor && selectedSize));
                } else {
                    $('#addToCartBtn').prop('disabled', false);
                }

                $('.select-any-size').toggle(hasColors && hasSizes && selectedSize === null);
                $('.select-any-color').toggle(hasColors && hasSizes && selectedColor === null);
            }

            $('.color-swatch').click(function () {
                selectedColor = {
                    id: $(this).data('color-id'),
                    productId: $(this).data('product-id'),
                };
                updateAddToCartButton();
                $('.color-swatch').removeClass('selected');
                $(this).addClass('selected');

                // // Update quantity input based on selected color
                // var qty = $(this).data('qty');
                // $('.quantity input').val(qty);
            });

            $('.size-swatch').click(function () {
                selectedSize = {
                    id: $(this).data('size-id'),
                    productId: $(this).data('product-id'),
                };
                updateAddToCartButton();
                $('.size-swatch').removeClass('selected');
                $(this).addClass('selected');

                // Update quantity input based on selected size
                // var qty = $(this).data('qty');
                // $('.quantity input').val(qty);
            });

            $('#addToCartBtn').click(function () {
                if (!hasSizes) {
                    // Handle the case where hasSizes is false
                    if (selectedColor !== null) {
                        var productId = null;

                        for (var key in variationData) {
                            if (variationData.hasOwnProperty(key)) {
                                var product = variationData[key];
                                var colorMatch = product.color.some(function (color) {
                                    return parseInt(color.id) === parseInt(selectedColor.id);
                                });

                                if (colorMatch) {
                                    productId = product['product-item-id'];
                                    break;
                                }
                            }
                        }

                        if (productId !== null) {
                            $.ajax({
                                type: 'POST',
                                url: '{{ route('front.item.add-to-cart') }}',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'product-item-id': productId,
                                    'selected-color': selectedColor.id,
                                    'selected-size': null,
                                },
                                success: function (data) {
                                    window.location.href = '{{ route("front.cart") }}';
                                },
                                error: function (error) {
                                    console.error('Error adding product to cart', error);
                                }
                            });
                        } else {
                            alert('This color is not available.');
                        }
                    } else {
                        alert('Please select a color before adding to cart');
                    }
                }
                else  if (!hasColors) {
                    // Handle the case where hasColors is false
                    if (selectedSize !== null) {
                        var productId = null;

                        for (var key in variationData) {
                            if (variationData.hasOwnProperty(key)) {
                                var product = variationData[key];
                                var sizeMatch = product.size.some(function (size) {
                                    return parseInt(size.id) === parseInt(selectedSize.id);
                                });

                                if (sizeMatch) {
                                    productId = product['product-item-id'];
                                    break;
                                }
                            }
                        }
                        // var quantity = $('.qty input').val();
                        //(parseInt(quantity) > 0)
                        if (productId !== null) {
                            $.ajax({
                                type: 'POST',
                                url: '{{ route('front.item.add-to-cart') }}',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'product-item-id': productId,
                                    'selected-Size': selectedSize.id,
                                    'selected-color': null,
                                },
                                success: function (data) {
                                    window.location.href = '{{ route("front.cart") }}';
                                },
                                error: function (error) {
                                    console.error('Error adding product to cart', error);
                                }
                            });
                        } else {
                            alert('This Size is not available.');
                        }
                    } else {
                        alert('Please select a Size before adding to cart');
                    }
                }
                else {
                    if (selectedColor !== null && selectedSize !== null ) {
                        var productId = null;

                        for (var key in variationData) {
                            if (variationData.hasOwnProperty(key)) {
                                var product = variationData[key];
                                var colorMatch = product.color.some(function (color) {
                                    return parseInt(color.id) === parseInt(selectedColor.id);
                                });

                                var sizeMatch = product.size.some(function (size) {
                                    return parseInt(size.id) === parseInt(selectedSize.id);
                                });

                                if (colorMatch && sizeMatch) {
                                    productId = product['product-item-id'];
                                    break;
                                }
                            }
                        }
                        // Get the selected quantity
                        var quantity = $('.quantity input').val();

                        if (productId !== null) {
                            $.ajax({
                                type: 'POST',
                                url: '{{ route('front.item.add-to-cart') }}',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'product-item-id': productId,
                                    'selected-color': selectedColor.id,
                                    'selected-size': selectedSize.id,
                                },
                                success: function (data) {
                                    window.location.href = '{{ route("front.cart") }}';
                                },
                                error: function (error) {
                                    console.error('Error adding product to cart', error);
                                }
                            });
                        } else {
                            alert('This size is not available.');
                        }
                    } else {
                        alert('Please select both color and size before adding to cart');
                    }
                }
            });
        });


    </script>

@endsection
