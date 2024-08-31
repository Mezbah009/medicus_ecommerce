@extends('front.layouts.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-gray">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                <li class="breadcrumb-item">Settings</li>
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
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Change Password</h2>
                    </div>
                    <form action='{{route("account.otpStorePassword") }}' method="POST">
                        @csrf
                        <div class="card-body p-4">
                            
                            <div class="row">
                                {{-- @if(($user !== null))
                                <div class="mb-3">               
                                    <input type="hidden" name="phone" id="phone" placeholder="New Password" value="{{$user}}" class="form-control">
                                    <p></p>
                                </div> 
                                @endif --}}
                                @if(session()->has('given_phone'))
                                <div class="mb-3">               
                                    <input type="text" name="phone" id="phone" placeholder="New Password" value="{{session()->get('given_phone')}}" class="form-control">
                                    <p></p>
                                </div> 
                              @endif
                                <div class="mb-3">               
                                    <label for="name">New Password</label>
                                    <input type="password" name="new_password" id="new_password" placeholder="New Password Minimum 5 letters" class="form-control">
                                    @error('new_password')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">               
                                    <label for="name">Confirm Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control">
                                    @error('confirm_password')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-dark">Save</button>
                                </div>
                            </div>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>
            
@endsection
@section('customJs')
{{-- <script>
    $("#changePassword").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled',true);
        $.ajax({
            url: '{{ route("account.otpStorePassword") }}',
            type: 'POST',
            data: element.serializeArray(),  // Fixed typo: 'data' instead of 'date'
            dataType: 'json',
            headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
            success: function(response) {  // Fixed typo: 'function' instead of 'funtion'
                // Handle success response here
                $("button[type=submit]").prop('disabled',false);
                if(response["status"] == true){
                    // window.location.href="{{route('account.showChangePasswordForm')}}"



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
</script> --}}
@endsection