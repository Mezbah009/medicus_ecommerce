
@extends('front.layouts.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-gray">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Forgot Password</li>
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
            <form action="{{route('front.processForgotPassword')}}" method="post" name="forgetPassword" id="forgetPassword">
                @csrf
                <h4 class="modal-title">Forgot Password</h4>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Phone" name="phone" id="phone"  value="{{old('phone')}}" required>
                    <p></p>

                </div>

                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Submit">
            </form>
            <div class="text-center small"><a href="{{route('account.login')}}">Login</a></div>
        </div>
    </div>
</section>
@endsection
@section('customJs')
<script>
    $("#forgetPassword").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled',true);
        $.ajax({
            url: '{{ route("front.processForgotPassword") }}',
            type: 'POST',
            data: element.serializeArray(),  // Fixed typo: 'data' instead of 'date'
            dataType: 'json',

            success: function(response) {  // Fixed typo: 'function' instead of 'funtion'
                // Handle success response here
                $("button[type=submit]").prop('disabled',false);
                if(response["status"] == true){
                    window.location.href="{{route('front.forgotPassword')}}"



                } else{
                    var errors = response['errors'];
                    $(".error").removeClass('is-invalid').html(''); // Remove error classes and clear error messages
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
        })
    });
</script>
@endsection

