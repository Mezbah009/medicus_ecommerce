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
            <div class="col-md-3">
               @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">

                <div class="card">
                    <form action="" name="profileForm" id="profileForm">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input value="{{$user->name}}" type="text" name="name" id="name" placeholder="Enter Your Name" class="form-control">
                                <p> </p>
                            </div>
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input value="{{$user->email}}" type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                <p> </p>
                            </div>
                            <div class="mb-3">
                                <label for="phone">Phone</label>
                                <input readonly value="{{$user->phone}}" type="text" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control">
                                <p> </p>
                            </div>

                            <div class="d-flex">
                                <button type="submit" class="btn btn-dark">Update</button>
                            </div>
                        </div>
                    </div>
              </form>
                </div>

                <div class="card mt-5">
                    <form action="" name="addressForm" id="addressForm">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Address</h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name">First Name</label>
                                <input value="{{(!empty($address)) ? $address->first_name : ''}}"  type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control">
                                <p> </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email">Last Name</label>
                                <input value="{{(!empty($address)) ? $address->last_name : ''}}"  type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control">
                                <p> </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone">Email</label>
                                <input value="{{(!empty($address)) ? $address->email : ''}}" type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                <p> </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone</label>
                                <input value="{{(!empty($address)) ? $address->mobile : ''}}" type="text" name="mobile" id="mobile" placeholder="Enter Your Email" class="form-control">
                                <p> </p>
                            </div>

                            <div class="mb-3">
                                <label for="phone">Country Id</label>
                                 <select name="country_id" id="country_id" class="form-control">
                                 <option value="">Select Country</option>
                                 @if($countries->isNotempty())
                                 @foreach($countries as $country)
                                 <option {{(!empty($address) && $address->country_id == $country->id) ? 'selected' : ''}} value="{{$country->id}}">{{$country->name}}</option>
                                 @endforeach
                                 @endif
                              </select>
                                <p> </p>
                            </div>

                            <div class=" mb-3">
                                <label for="content"> Address </label>
                                <textarea name="address" id="address" class="form-control" cols="30" rows="10" >{{(!empty($address)) ? $address->address : ''}}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone">Apartment</label>
                                <input  type="text" name="apartment" id="apartment" placeholder="Enter Your Apartment" class="form-control" value="{{(!empty($address)) ? $address->apartment : ''}}">
                                <p> </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone">City</label>
                                <input  type="text" name="city" id="city" placeholder="Enter Your City" class="form-control" value="{{(!empty($address)) ? $address->city : ''}}">
                                <p> </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone">State</label>
                                <input type="text" name="state" id="state" placeholder="Enter Your State" class="form-control" value="{{(!empty($address)) ? $address->state : ''}}">
                                <p> </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone">Zip</label>
                                <input type="text" name="zip" id="zip" placeholder="Enter Your Zip" class="form-control" value="{{(!empty($address)) ? $address->zip : ''}}">
                                <p> </p>
                            </div>

                            <div class="d-flex">
                                <button type="submit" class="btn btn-dark">Update</button>
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
<script>
$("#profileForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled',true);
    $.ajax({
        url: '{{ route("account.updateProfile") }}',
        type: 'put',
        data: element.serializeArray(),  // Fixed typo: 'data' instead of 'date'
        dataType: 'json',
        headers: {

    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
        success: function(response) {  // Fixed typo: 'function' instead of 'funtion'
            // Handle success response here
            $("button[type=submit]").prop('disabled',false);
            if(response["status"] == true){
                window.location.href="{{route('account.profile')}}"



            } else{
                var errors = response['errors'];
                $(".error").removeClass('is-invalid').html(''); // Remove error classes and clear error messages
                $("input[type='text'], select").removeClass('is-invalid');
                $.each(errors, function(key, value) {
                    $(`#profileForm #${key}`).addClass('is-invalid'); // Add the 'is-invalid' class to the input
                    $(`#profileForm #${key}`).next('p').addClass('invalid-feedback').html(value); // Add the error message
                });

            }

        },
        error: function(jqXHR, exception) {
            console.log("Something went wrong");
        }
    })
});

// Address Form
$("#addressForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled',true);
    $.ajax({
        url: '{{ route("account.updateAddress") }}',
        type: 'put',
        data: element.serializeArray(),  // Fixed typo: 'data' instead of 'date'
        dataType: 'json',
        headers: {

    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
        success: function(response) {  // Fixed typo: 'function' instead of 'funtion'
            // Handle success response here
            $("button[type=submit]").prop('disabled',false);
            if(response["status"] == true){
                window.location.href="{{route('account.profile')}}"



            } else{
                var errors = response['errors'];
                $(".error").removeClass('is-invalid').html(''); // Remove error classes and clear error messages
                $("input[type='text'], select").removeClass('is-invalid');
                $.each(errors, function(key, value) {
                    $(`#addressForm #${key}`).addClass('is-invalid'); // Add the 'is-invalid' class to the input
                    $(`#addressForm #${key}`).next('p').addClass('invalid-feedback').html(value); // Add the error message
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
