@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Shipping Management</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('shipping.create')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
    @include('admin.message')
    <form action="" method="POST" id="shippingForm" name="shippingForm">
        {{-- @csrf --}}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <select name="country" id="country" class="form-control">
                                <option value="">Select a Country</option>
                            @if($countries->isNotEmpty())
                                @foreach($countries as $country)
                                <option {{($shippingCharge->country_id == $country->id) ? 'selected' : ''}} value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach
                                <option {{($shippingCharge->country_id == 'rest_of_world') ? 'selected' : ''}} value="rest_of_world">Rest of the world</option>
                            @endif
                            </select>
                            <p></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <input value="{{$shippingCharge->amount}}" type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                            <p></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary" >Update</button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
</form>
</div>

    <!-- /.card -->
</section>
<!-- /.content -->
@endsection
@section('customJs')

<script>

      $("#shippingForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled',true);

        $.ajax({
            url: '{{ route("shipping.update", $shippingCharge->id) }}',
            type: 'Put',
            data: element.serializeArray(),  // Fixed typo: 'data' instead of 'date'
            dataType: 'json',
            headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
            success: function(response) {  // Fixed typo: 'function' instead of 'funtion'
                // Handle success response here
                $("button[type=submit]").prop('disabled',false);

                if(response["status"] == true){
                    window.location.href="{{route('shipping.create')}}"
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
