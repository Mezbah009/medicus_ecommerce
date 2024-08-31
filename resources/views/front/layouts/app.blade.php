<!DOCTYPE html>
<html class="no-js" lang="en_AU" />
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Medicus Medicine Shop</title>
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />

	<meta name="HandheldFriendly" content="True" />
	<meta name="pinterest" content="nopin" />

	<meta property="og:locale" content="en_AU" />
	<meta property="og:type" content="website" />
	<meta property="fb:admins" content="" />
	<meta property="fb:app_id" content="" />
	<meta property="og:site_name" content="" />
	<meta property="og:title" content="" />
	<meta property="og:description" content="" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />
	<meta property="og:image:type" content="image/jpeg" />
	<meta property="og:image:width" content="" />
	<meta property="og:image:height" content="" />
	<meta property="og:image:alt" content="" />

	<meta name="twitter:title" content="" />
	<meta name="twitter:site" content="" />
	<meta name="twitter:description" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:image:alt" content="" />
	<meta name="twitter:card" content="summary_large_image" />

    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick-theme.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/ion.rangeSlider.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/custom.css') }}" />

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

	<!-- Fav Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="{{ asset('front-assets/images/favicon.ico') }}">

    <!-- Other head elements -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body data-instant-intensity="mousedown">

    <nav class="navbars navbar-expand-lg navbar-light">
        <!-- Collection of nav links, forms, and other content for toggling -->
        <div id="navbarCollapse" class="collapse navbar-collapse justify-content-start">
            <div class="navbar-nav">
                <a href="#" class="nav-item nav-link"><i class="fa fa-truck"></i> Free Delivery</a>
                <a href="#" class="nav-item nav-link"><i class="fa fa-spinner"></i> Returns Policy</a>
                <a href="#" class="nav-item nav-link">Follow Us : </a>
            </div>
                <a href="#" class="nav-item nav-link"><i class="fab fa-facebook-f text-white"></i></a>
                <a href="#" class="nav-item nav-link"><i class="fab fa-instagram text-white"></i></a>
            </div>
        </div>
    </nav>




    <div class="bg-white top-header">
        <div class="container">
            <div class="row align-items-center py-3 d-none d-lg-flex justify-content-between">
                <div class="col-lg-3 col-12 logo"> <!-- Adjusted col-lg-3 to accommodate the logo -->
                    <a href="{{ route('front.home') }}" class="text-decoration-none">
                        @if(getLogo()->isNotEmpty())
                            <img src="{{ asset('uploads/logo/'.getLogo()->first()->image) }}" alt="logo" class="logo">
                        @endif
                    </a>
                </div>
                <div class="col-lg-6 col-12 d-flex align-items-center justify-content-center"> <!-- Adjusted col-lg-6 to accommodate the search input -->
                    <form action="{{ route('front.shop') }}" class="w-100"> <!-- Added w-100 to make the form width 100% -->
                        <div class="input-group">
                            <input value="{{ Request::get('search') }}" type="text" placeholder="Search For Products" class="form-control" name="search">
                            <button type="submit" class="input-group-text">
                                <i class="fa fa-search text-white"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-1 col-12 text-right d-flex justify-content-end align-items-center"> <!-- Adjusted col-lg-3 to accommodate the login/register link -->
                    @if(Auth::check())
                        <a href="{{ route('account.profile') }}" class="nav-link text-dark"> <i> <svg class="nav-icons" xmlns="http://www.w3.org/2000/svg" height="25px" width="25px" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464H398.7c-8.9-63.3-63.3-112-129-112H178.3c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3z"/></svg></i></a>
                    @else
                        <a href="{{ route('account.login') }}" class="nav-link text-dark"> <i>
                            <svg class="nav-icons" xmlns="http://www.w3.org/2000/svg" height="25px" width="25px" fill="black" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464H398.7c-8.9-63.3-63.3-112-129-112H178.3c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3z"/></svg>
                            </i></a>
                    @endif
                    <div class="col-lg-2 col-12 text-right d-flex justify-content-end align-items-center">
                        <a href="{{ route('account.wishlist') }}" class="ml-3 d-flex pt-2">

                                <i>
                                    <svg class="nav-icons" xmlns="http://www.w3.org/2000/svg" height="25px" width="25px" fill="black" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8v-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5v3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20c0 0-.1-.1-.1-.1c0 0 0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5v3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2v-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"/></svg>
                                </i>


                        </a>
                    </div>
                     <div class="col-lg-6 col-12 text-right d-flex justify-content-end align-items-center">
                    <a href="{{ route('front.cart') }}" class="ml-3 d-flex pt-2">
                        <div class="cart-icon-container">
                            <i>
                                <svg xmlns="http://www.w3.org/2000/svg" height="30px" width="30px" viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M423.3 440.7c0 25.3-20.3 45.6-45.6 45.6s-45.8-20.3-45.8-45.6 20.6-45.8 45.8-45.8c25.4 0 45.6 20.5 45.6 45.8zm-253.9-45.8c-25.3 0-45.6 20.6-45.6 45.8s20.3 45.6 45.6 45.6 45.8-20.3 45.8-45.6-20.5-45.8-45.8-45.8zm291.7-270C158.9 124.9 81.9 112.1 0 25.7c34.4 51.7 53.3 148.9 373.1 144.2 333.3-5 130 86.1 70.8 188.9 186.7-166.7 319.4-233.9 17.2-233.9z"/></svg>
                            </i>
                            @if (getTotalcart() > 0)
                                <span class="badge badge-danger">{{ getTotalcart() }}</span>
                            @endif
                        </div>
                    </a>
                </div>
                </div>

            </div>
        </div>
    </div>

<header class="bg-white">
	<div class="container">
		<nav class="navbar navbar-expand-xl" id="navbar">
			<a href="index.php" class="text-decoration-none mobile-logo">
				{{-- <span class="h2 text-uppercase text-primary bg-dark">Online</span> --}}
				{{-- <span class="h2 text-uppercase text-success px-2">SHOP</span> --}}
			</a>
			<button class="navbar-toggler menu-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      		<!-- <span class="navbar-toggler-icon icon-menu"></span> -->
				<i class="navbar-toggler-icon fas fa-bars"></i>
    		</button>
    		<div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
      		<ul class="navbar-nav mb-2 mb-lg-0 mx-auto">
        		<!-- <li class="nav-item">
          		<a class="nav-link active" aria-current="page" href="index.php" title="Products">Home</a>
        		</li> -->
                <li class="nav-item">
          		<a class="btn btn-dark" aria-current="page" href="{{route('front.home')}}" title="Products">Home</a>
        		</li>
                <li class="nav-item">
                    <a class="btn btn-dark" aria-current="page" href="index.php" title="Products">About Us</a>
                  </li>

                <li class="nav-item dropdown">
                    <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Food
                    </button>
                    @if (getCategories()->isNotEmpty())
                    <ul class="dropdown-menu dropdown-menu-dark">
                        @foreach ( getCategories() as $category)
                        <li><a class="dropdown-item nav-link" href="{{route('front.shop', [$category->slug])}}">{{ $category->name }}</a></li>
                        @endforeach
                </li>
                </ul>
                    @endif
                    <li class="nav-item">
                        <a href="https://medicus.opusdemo.com/public/">
                        <button class="btn btn-warning" data-bs-toggle="" aria-expanded="false">
                                Doctor
                        </button>
                        </a>
                    </li>

      		</ul>

      		</div>




            {{--			<div class="right-nav py-0">--}}
{{--				<a href="{{route('front.shop')}}" class="ml-3 d-flex pt-2">--}}
{{--					<i class="fas fa-shopping-cart text-primary"></i>--}}
{{--				</a>--}}
{{--			</div>--}}
      	</nav>
  	</div>
</header>


<main>
@yield('content')
</main>
<footer class="bg-dark-footer mt-5">
	<div class="container pb-5 pt-3">
		<div class="row">
			<div class="col-md-4">
				<div class="footer-card">
                <h3>Get In Touch</h3>
                    <div class="col-lg-6 logo">
                        <a href="{{ route('front.home') }}" class="text-decoration-none">
                            @if(getLogo()->isNotEmpty())
                                <img src="{{ asset('uploads/logo/'.getLogo()->first()->image) }}" alt="logo" class="logo">
                            @endif
                        </a>
                    </div>
					@if (getLogo()->count() > 0 && getLogo()->first()->description !== null)
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        {{ getLogo()->first()->description }}
                   </div>
                    @endif

					{{-- <p>No dolore ipsum accusam no lorem. <br>
					kakrail, Dhaka, Bangladesh <br>
					opus@gmail.com <br>
					000 000 0000</p> --}}
				</div>
			</div>

			<div class="col-md-4">
				<div class="footer-card">
					<h3>Important Links</h3>
					<ul>
						@if (staticPages()->isNotEmpty())
							@foreach (staticPages() as $page)
							<li><a href="{{route('front.page',$page->slug)}}" title="About">{{$page->name}}</a></li>
							@endforeach


						@endif
						{{-- <li><a href="about-us.php" title="About">About</a></li>
						<li><a href="contact-us.php" title="Contact Us">Contact Us</a></li>
						<li><a href="#" title="Privacy">Privacy</a></li>
						<li><a href="#" title="Privacy">Terms & Conditions</a></li>
						<li><a href="#" title="Privacy">Refund Policy</a></li> --}}
					</ul>
				</div>
			</div>

			<div class="col-md-4">
				<div class="footer-card">
					<h3>My Account</h3>
					<ul>
						<li><a href="{{ route('account.login') }}" title="Sell">Login</a></li>
						<li><a href="{{ route('front.account.register') }}" title="Advertise">Register</a></li>
						<li><a href="#" title="Contact Us">My Orders</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
    <div class="copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-3 mb-3">
                    <div class="copy-right text-center">
                        <p>&copy; <span id="currentYear"></span> <a target="_blank" href="https://opus-bd.com/">Opus Technology Limited</a> All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</footer>
<!--Wishlist Modal -->
<div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Success</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>


<script>
    // Get the current year
    var currentYear = new Date().getFullYear();

    // Update the content of the 'currentYear' span with the current year
    document.getElementById('currentYear').textContent = currentYear;
</script>
<script src="{{ asset('front-assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
<script src="{{ asset('front-assets/js/instantpages.5.1.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/lazyload.17.6.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/slick.min.js') }}"></script>
<script src="{{ asset('front-assets/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('front-assets/js/custom.js') }}"></script>
<script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

window.onscroll = function() {myFunction()};

var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;

function myFunction() {
    if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
    } else {
    navbar.classList.remove("sticky");
    }
}
function addToCart(id) {
            $.ajax({
                url: '{{route("front.add-to-cart")}}',
                type: 'post',
                data: {id:id}, // You can include data to send to the server if needed
                dataType: 'json',
                success: function (response) {
                    if (response.status == true){
                        window.location.href = "{{ route('front.cart') }}";
                    }
                    else{
                        alert(response.message);
                    }
                }
            });
        }
    function addItemToCart(id) {
        $.ajax({
            url: '{{route("front.item.add-to-cart")}}',
            type: 'post',
            data: {id:id},
            dataType: 'json',
            success: function (response) {
                if (response.status == true){
                    window.location.href = "{{ route('front.cart') }}";
                }
                else{
                    alert(response.message);
                }
            }
        });
    }

        function addToWishlist(id){
            $.ajax({
                url: '{{route("front.addToWishlist")}}',
                type: 'post',
                data: {id:id}, // You can include data to send to the server if needed
                dataType: 'json',
                success: function (response) {
                    if (response.status == true){

                        $("#wishlistModal .modal-body").html(response.message);
                        $("#wishlistModal").modal('show');

                    }
                    else{
                        window.location.href = "{{ route('account.login') }}";
                        // alert(response.message);
                    }
                }
            });

        }
    function addToItemWishlist(id){
        $.ajax({
            url: '{{route("front.addToItemWishlist")}}',
            type: 'post',
            data: {id:id}, // You can include data to send to the server if needed
            dataType: 'json',
            success: function (response) {
                if (response.status == true){

                    $("#wishlistModal .modal-body").html(response.message);
                    $("#wishlistModal").modal('show');

                }
                else{
                    window.location.href = "{{ route('account.login') }}";
                    // alert(response.message);
                }
            }
        });

    }



</script>
@yield('customJs')
</body>
</html>
