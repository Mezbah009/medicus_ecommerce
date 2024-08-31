
@extends('front.layouts.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-gray">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Register</li>
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
            <form action="{{ route("account.processRegister") }}" method="post" id="registrationForm" name="registrationForm">
                @csrf
                <h4 class="modal-title">Register Now</h4>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" id="name" name="name" value="{{old('name')}}">
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Email" id="email" name="email" value="{{old('email')}}">
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone" value="{{old('phone')}}">
                    @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password" >
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation" >
                    @error('password_confirmation')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group small">
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
            </form>
            <div class="text-center small">Already have an account? <a href="{{route('account.login')}}">Login Now</a></div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script type="text/javascript">
    // $("#registrationForm").submit(function(event) {
    //     event.preventDefault();  // Corrected syntax: event.preventDefault() instead of event.preventDefault

    //     $.ajax({
    //         url: '{{ route("account.processRegister") }}',
    //         type: 'post',
    //         data: $(this).serializeArray(),
    //         dataType: 'json',
    //         success: function(response) {
    //             if (response.status === true && response.generate) {
    //     window.location.href = "{{ route('account.otpVerify', '') }}/" + response.generate;
    // }
    //             else{
    //                 var errors = response['errors'];
    //                 $("button[type=submit]").prop('disabled',false);
    //                 $(".error").removeClass('is-invalid').html(''); // Remove error classes and clear error messages
    //                 $("input[type='text'], select, input[type='number']").removeClass('is-invalid');
    //                 $.each(errors, function(key, value) {
    //                     $(`#${key}`).addClass('is-invalid'); // Add the 'is-invalid' class to the input
    //                     $(`#${key}`).next('p').addClass('invalid-feedback').html(value); // Add the error message
    //                 });
    //             }
    //         },
    //         error: function(jqXHR, exception) {
    //             console.log("Something went wrong");

    //         }
    //     });
    // });
</script>


@endsection
