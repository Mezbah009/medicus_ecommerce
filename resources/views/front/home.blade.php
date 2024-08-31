@extends('front.layouts.app')
@section('content')
    <section class="section-1">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner">
                @if ($slider->isNotEmpty())
                    @foreach ($slider as $key => $sliders)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <picture>
                        <source media="(max-width: 1365px)" srcset="{{ asset('uploads/slider/'.$sliders->image) }}" />
                        <source media="(min-width: 1365px)" srcset="{{ asset('uploads/slider/'.$sliders->image) }}" />
                        <img src="{{ asset('uploads/slider/'.$sliders->image) }}" alt="" class="img-fluid">
                        </picture>
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3">
                                <p class="text-success">{{$sliders->description}}</p>
                                <h1 class="display-4 text-black mb-3">{{$sliders->title}}</h1>

                                <a class="btn btn-success  fw-bolder" href="{{$sliders->link}}"  > {{$sliders->button_name}}</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <section class="section-2">
    <div class="ps-site-features">
		<div class="ps-container">
			<div class="ps-block--site-features">
				<div class="ps-block__item">
					<div class="ps-block__left">
			        <img src="{{asset('front-assets/images/fast-delivery.png')}}" alt="Icon Alt Text" class="icon-shipping">
					</div>
					<div class="ps-block__right">
						<h4>Fast Shipping</h4>
						<p>
                            For minimum order BDT 999Tk
						</p>
					</div>
				</div>
				<div class="ps-block__item">
					<div class="ps-block__left">
                        <img src="{{asset('front-assets/images/pharmacy.png')}}" alt="Icon Alt Text" class="icon-size">
					</div>
					<div class="ps-block__right">
						<h4>Pure Health</h4>
						<p>
                            Medicine You Can Trust
						</p>
					</div>
				</div>
				<div class="ps-block__item">
					<div class="ps-block__left">
                    <img src="{{asset('front-assets/images/money.png')}}" alt="Icon Alt Text" class="icon-size">
					</div>
					<div class="ps-block__right">
						<h4>Secure payment</h4>
						<p>
                        100% secure payment
						</p>
					</div>
				</div>
				<div class="ps-block__item">
					<div class="ps-block__left">
                    <img src="{{asset('front-assets/images/24-hours-support.png')}}" alt="Icon Alt Text" class="icon-size">
					</div>
					<div class="ps-block__right">
						<h4>Endless support</h4>
						<p>
                        Exclusive support dedicated to you
						</p>
					</div>
				</div>
				<div class="ps-block__item">
					<div class="ps-block__left">
                    <img src="{{asset('front-assets/images/gift.png')}}" alt="Icon Alt Text" class="icon-gift">
					</div>
					<div class="ps-block__right">
						<h4>Gift Vouchers</h4>
						<p>
                        Celebrate in style without breaking the bank!
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section-4 pt-50">
    <div class="ps-container">
        <div class="section-title">
            <h2>Menu Items</h2>
        </div>
        <div class="ps-block__item">
        <div class="row-cat pb-3">
            @if (!getCategories()->isEmpty())
            @foreach (getCategories() as $category)

                    <div class="cat-cardCategory">
                        <div class="upper-container">
                        <a href="{{route('front.shop', $category->slug)}}" }}">
                            @if ($category->image !== "")
                            <img src="{{ asset('uploads/category/thumb/'.$category->image) }}" alt="" class="img-fluid">
                        @endif
                        </div>
                        <div class="lower-container">

                            <h2>{{ $category->name }}</h2>
                            </a>
                        </div>
                    </div>


            @endforeach
        @endif
        </div>
        </div>
    </div>
</section>

<section class="section-4 pt-5">
    <div class="ps-container">
        <div class="section-title">
            <h2>Trending Items</h2>
        </div>
        <div class="row pb-3">

            @if ($featuredProducts->isNotEmpty())
            @foreach ($featuredProducts as $featuredProduct)
                @php
                $productImage = $featuredProduct->product_images->first();
                @endphp
                <div class="col-md-3">
                    <div class="card product-card">
                        <div class="product-image position-relative">
                            <a href="{{ route("front.product", $featuredProduct->slug) }}" class="product-img"> <!-- Use $featuredProduct here -->
                                @if (!empty($productImage->image))
                                    <img class="card-img-top" src="{{ asset('uploads/product/small/' . $productImage->image) }}" class="img-thumbnail" alt="{{ $featuredProduct->title }}" height="150px" width="150px">
                                @else
                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="default image" height="150px" width="150px">
                                @endif
                            </a>
                            @if($featuredProduct->product_items != null && $featuredProduct->product_items->isNotEmpty())
                                @php
                                    $item = $featuredProduct->product_items->first();
                                @endphp
                                <a onclick="addToItemWishlist({{ $item->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>
                            @else
                                <a onclick="addToWishlist({{ $featuredProduct->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>
                            @endif
                            <div class="product-action">
                                @if($featuredProduct->track_qty == 'Yes')
                                @if ($featuredProduct->qty > 0)
                                    @if($featuredProduct->product_items != null && $featuredProduct->product_items->isNotEmpty())
                                        @php
                                            $item = $featuredProduct->product_items->first();
                                        @endphp
                                        @if($item->qty > 0)
                                            <a href="javascript:void(0)" onclick="addItemToCart({{$item->id}})" class="btn btn-dark">
                                                <i class="fas fa-shopping-cart"></i> Add To Cart
                                            </a>
                                            @else
                                                <a class="btn btn-dark" href="{{ route("front.product", $featuredProduct->slug) }}" >
                                                    Add To Cart
                                                </a>
                                        @endif
                                    @else
                                        <a href="javascript:void(0)" onclick="addToCart({{$featuredProduct->id}})" class="btn btn-dark">
                                            <i class="fas fa-shopping-cart"></i> Add To Cart
                                        </a>
                                    @endif
                                @else
                                <a class="btn btn-dark" href="javascript:void(0)" >
                                    Out Of Stock
                                </a>
                                @endif
                                @else
                                    @if($featuredProduct->product_items != null && $featuredProduct->product_items->isNotEmpty())
                                        @php
                                            $item = $featuredProduct->product_items->first();
                                        @endphp
                                        <a href="javascript:void(0)" onclick="addItemToCart({{$item->id}})" class="btn btn-dark">
                                            <i class="fas fa-shopping-cart"></i> Add To Cart
                                        </a>
                                    @else
                                        <a href="javascript:void(0)" onclick="addToCart({{$featuredProduct->id}})" class="btn btn-dark">
                                            <i class="fas fa-shopping-cart"></i> Add To Cart
                                        </a>
                                    @endif
                                @endif

                            </div>
                        </div>
                        <div class="card-body text-center mt-3">
                            <a class="h6 link" href="{{ route("front.product", $featuredProduct->slug) }}">{{ $featuredProduct->title }}</a>
                            <div class="price mt-2">
                                @if($featuredProduct->product_items != null && $featuredProduct->product_items->isNotEmpty())
                                    @php
                                        $item = $featuredProduct->product_items->first();
                                    @endphp
                                    <span class="h5"><strong>৳{{ $item->price }}</strong></span>
                                    @if ($item->compare_price > 0)
                                        <span class="h6 text-underline"><del>৳{{ $item->compare_price }}</del></span>
                                    @endif
                                @else
                                    <span class="h5"><strong>৳{{ $featuredProduct->price }}</strong></span>
                                    @if ($featuredProduct->compare_price > 0)
                                        <span class="h6 text-underline"><del>৳{{ $featuredProduct->compare_price }}</del></span>
                                    @endif
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        </div>
    </div>
</section>

<section class="section-2">
    <div class="ps-site-features">
        <div class="ps-container">
            <img src="{{asset('front-assets/images/medicine_shop.png')}}" alt="Icon Alt Text" >
        </div>
    </div>
</section>

<section class="section-4 pt-5">
    <div class="ps-container">
        <div class="section-title">
            <h2>New Items</h2>
        </div>
        <div class="row pb-3">
            @if ($latestProducts->isNotEmpty())
            @foreach ($latestProducts as $latestProducts)
                {{-- @foreach ($products as $product) --}}
                    @php
                    $productImage = $latestProducts->product_images->first();
                    @endphp
                    <div class="col-md-3">
                        <div class="card product-card">
                            <div class="product-image position-relative">
                                <a href="{{ route("front.product", $latestProducts->slug) }}" class="product-img">

                                    @if (!empty($productImage->image))
                                        <img class="card-img-top" src="{{ asset('uploads/product/small/' . $productImage->image) }}" class="img-thumbnail" alt="{{ $latestProducts->title }}" height="150px" width="150px" />
                                    @else
                                        <img src="{{ asset('admin-assets/img/default.png') }}"  alt="default image" height="150px" width="150px" />
                                    @endif

                                </a>
                                @if($latestProducts->product_items != null && $latestProducts->product_items->isNotEmpty())
                                    @php
                                        $item = $latestProducts->product_items->first();
                                    @endphp
                                    <a onclick="addToItemWishlist({{ $item->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>
                                @else
                                    <a onclick="addToWishlist({{ $latestProducts->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>
                                @endif
                                <div class="product-action">
                                    @if($latestProducts->track_qty == 'Yes')
                                        @if ($latestProducts->qty > 0)
                                            @if($latestProducts->product_items != null && $latestProducts->product_items->isNotEmpty())
                                                @php
                                                    $item = $latestProducts->product_items->first();
                                                @endphp
                                                @if($item->qty > 0)
                                                    <a href="javascript:void(0)" onclick="addItemToCart({{$item->id}})" class="btn btn-dark">
                                                        <i class="fas fa-shopping-cart"></i> Add To Cart
                                                    </a>
                                                @else
                                                    <a class="btn btn-dark" href="{{ route("front.product", $latestProducts->slug) }}" >
                                                        Add To Cart
                                                    </a>
                                                @endif
                                            @else
                                                <a href="javascript:void(0)" onclick="addToCart({{$latestProducts->id}})" class="btn btn-dark">
                                                    <i class="fas fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            @endif
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0)" >
                                                Out Of Stock
                                            </a>
                                        @endif
                                    @else
                                        @if($latestProducts->product_items != null && $latestProducts->product_items->isNotEmpty())
                                            @php
                                                $item = $latestProducts->product_items->first();
                                            @endphp
                                            <a href="javascript:void(0)" onclick="addItemToCart({{$item->id}})" class="btn btn-dark">
                                                <i class="fas fa-shopping-cart"></i> Add To Cart
                                            </a>
                                        @else
                                            <a href="javascript:void(0)" onclick="addToCart({{$latestProducts->id}})" class="btn btn-dark">
                                                <i class="fas fa-shopping-cart"></i> Add To Cart
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="card-body text-center mt-3">
                                <a class="h6 link" href="{{ route("front.product", $latestProducts->slug) }}">{{ $latestProducts->title }}</a>
                                <div class="price mt-2">
                                    @if($latestProducts->product_items != null && $latestProducts->product_items->isNotEmpty())
                                        @php
                                            $item = $latestProducts->product_items->first();
                                        @endphp
                                        <span class="h5"><strong>৳{{ $item->price }}</strong></span>
                                        @if ($item->compare_price > 0)
                                            <span class="h6 text-underline"><del>৳{{ $item->compare_price }}</del></span>
                                        @endif
                                    @else
                                        <span class="h5"><strong>৳{{ $latestProducts->price }}</strong></span>
                                        @if ($latestProducts->compare_price > 0)
                                            <span class="h6 text-underline"><del>৳{{ $latestProducts->compare_price }}</del></span>
                                        @endif
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                {{-- @endforeach --}}
            @endforeach
        @endif

        </div>
    </div>
</section>
@endsection
