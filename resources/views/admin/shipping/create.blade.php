@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-10">
                <h1>Shipping Management</h1>
            </div>
            <div class="col-sm-2">
                <a href="{{route('weights.list')}}"  class="btn btn-primary">Weight wise shipping</a>

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
                                <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach
                                <option value="rest_of_world">Rest of the world</option>
                            @endif
                            </select>
                            <p></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                            <p></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary" >Create</button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</form>

<div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            @if($shippingCharges->isNotempty())
                            @foreach($shippingCharges as $shippingCharge)
                            <tr>
                                <td>{{$shippingCharge->id}}</td>
                                <td>
                                    {{($shippingCharge->country_id == 'rest_of_world') ? 'Rest of the World' : $shippingCharge->name}}
                                </td>
                                <td>৳ {{$shippingCharge->amount}}</td>
                                <td>
                                    <a href="{{route('shipping.edit',$shippingCharge->id)}}" class="btn btn-primary">Edit</a>
                                    <a href="javascript:void(0);" onclick="deleteRecord({{$shippingCharge->id}});" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </table>

                    </div>
                </div>
            </div>
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
            url: '{{ route("shipping.store") }}',
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

    function deleteRecord(id){
    var url = '{{ route("shipping.delete", "ID") }}';

    var newUrl  = url.replace("ID",id)
    if (confirm("Are you sure you wamt to delete")) {
        $.ajax({
    url: newUrl,
    type: 'delete',
    data: {},  // Fixed typo: 'data' instead of 'date'
    dataType: 'json',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response) {  // Fixed typo: 'function' instead of 'funtion'
        // Handle success response here
        $("button[type=submit]").prop('disabled', false);
        if (response["status"]) {
            window.location.href = "{{route('shipping.create')}}";
        } else {
            // Handle other cases if needed
        }
    }  // Fixed typo: removed extra closing parenthesis
});
    }
}

</script>


@endsection
