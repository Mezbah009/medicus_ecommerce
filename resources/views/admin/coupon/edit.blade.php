@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Coupon Code</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('coupons.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <form action="" method="POST" id="discountForm" name="discountForm">

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Code</label>
                            <input type="text" name="code" id="code" class="form-control" placeholder="Coupon Code" value = "{{$coupon->code}}">
                            <p class="error"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Coupon Code Name" value = "{{$coupon->name}}">
                            <p class="error"></p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Max Uses</label>
                            <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="Max Uses" value = "{{$coupon->max_uses}}">
                            <p class="error"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Max Uses User</label>
                            <input type="number" name="max_uses_user" id="max_uses_user" class="form-control" value = "{{$coupon->Max_uses_user}}">
                            <p class="error"></p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="Percent">Type</label>
                            <select  name="type" id="type" class="form-control" >
                                <option {{($coupon->type =='percent')? 'selected' : ''}} value='percent'>Percent</option>
                                <option {{($coupon->type =='fixed')? 'selected' : ''}} value='fixed'>Fixed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Discount Amount</label>
                            <input type="number" name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount Amount" value = "{{$coupon->discount_amount}}">
                            <p class="error"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Min Amount</label>
                            <input type="number" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount" value = "{{$coupon->min_amount}}">
                            <p class="error"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Starts At</label>
                            <input type="text" autocomplete="off" name="starts_at" id="starts_at" class="form-control" placeholder="Starts At" value = "{{$coupon->starts_at}}">
                            <p class="error"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Expires At</label>
                            <input type="text" autocomplete="off" name="expires_at" id="expires_at" class="form-control" placeholder="Expires At" value = "{{$coupon->expires_at}}">
                            <p class="error"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status">status</label>
                            <select  name="status" id="status" class="form-control" >
                                <option {{($coupon->status ==1)? 'selected' : ''}} value="1">Active</option>
                                <option {{($coupon->status ==0)? 'selected' : ''}} value="0">Deactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="text">Description</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="5" >{{$coupon->description}}</textarea>
                            <p class="error"></p>
                        </div>
                    </div>   
                    </div>
                </div>
            </div>
        </div>
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary" >Update</button>
            <a href="{{route('categories.create')}}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
    </div>
</form>

    <!-- /.card -->
</section>
<!-- /.content -->
@endsection
@section('customJs')

<script>

    $(document).ready(function(){
            $('#starts_at').datetimepicker({
                // options here
                format:'Y-m-d H:i:s',
            });

            $('#expires_at').datetimepicker({
                // options here
                format:'Y-m-d H:i:s',
            });
        });

    $("#discountForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled',true);
        $.ajax({
            url: '{{ route("coupons.update",$coupon->id) }}',
            type: 'PUT',
            data: element.serializeArray(),  // Fixed typo: 'data' instead of 'date'
            dataType: 'json',
            headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
            success: function(response) {  // Fixed typo: 'function' instead of 'funtion'
                // Handle success response here
                $("button[type=submit]").prop('disabled',false);
                if(response["status"] == true){
                    window.location.href="{{route('coupons.index')}}"



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
