
@extends('front.layouts.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-gray">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
                <li class="breadcrumb-item">Otp Verification</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        @if (Session::has('success'))
        <div class = "alert alert-success">
            {{Session::get('success')}}
        </div>
        @endif
        @if (Session::has('error'))
        <div class = "alert alert-danger">
            {{Session::get('error')}}
        </div>
        @endif
        <div class="login-form">
            <form action="{{route('account.generateOtp')}}" method="post">
                @csrf
                <h4 class="modal-title">Login to Your Account</h4>
                <div class="form-group">
                    <input type="text" class="form-control"   @error('phone') is-invalid  @enderror placeholder="Enter valid Phone Number" name="phone" id="phone">
                    @error('phone')
                        <p class="invalid-feedback" >{{ $message }}</p>
                    @enderror
                </div>
                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Generate Otp">
            </form>
            <div class="text-center small">Don't have an account? <a href="{{route('front.account.register')}}">Sign up</a>
            </div>
        </div>
    </div>
</section>
@endsection

