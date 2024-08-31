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
        <div >
            @include('front.account.common.message')
        </div>


        <div class="login-form">

            <form action="{{route('account.otpRegister')}}" method="post">
               <div id="countdown">3:00</div>

                @csrf
                <h4 class="modal-title">Login to Your Account</h4>
                <div class="form-group">
                    @if($user !== null)
                    <input type="hidden" class="form-control" value="{{$user['name']}}" name="name" id="name">
                    <input type="hidden" class="form-control" value="{{$user['email']}}" name="email" id="email">
                    <input type="hidden" class="form-control" value="{{$user['phone']}}" name="phone" id="phone">
                    <input type="hidden" class="form-control" value="{{$user['password']}}" name="password" id="password">
                    @endif

                    <input type="hidden" class="form-control" value="{{$id}}" name="otpID" id="otpID">
                    <input type="number" class="form-control"  name="otp" id="otp" value="{{ $user['opt'] }}">
                    @error('phone')
                        <p class="invalid-feedback" >{{ $message }}</p>
                    @enderror
                </div>
                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Verify">
            </form>
            <div class="text-center small">Don't have an account? <a href="{{route('front.account.register')}}">Sign up</a>
            </div>
        </div>
    </div>
</section>
@endsection
@section('customJs')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    var countdown;
    var time = 180; // 3 minutes in seconds

    function startCountdown() {
      countdown = setInterval(function () {
        var minutes = Math.floor(time / 60);
        var seconds = time % 60;

        var display = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
        $('#countdown').html(display);

        if (time <= 0) {
          clearInterval(countdown);
          $('#countdown').html('0:00');
        } else {
          time--;
        }
      }, 1000);
    }

    $('#start').click(function () {
      startCountdown();
    });

    // Automatically start the countdown when the page loads
    startCountdown();

</script>



@endsection

