@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Variation Option Details</h1>
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
    <form action="{{ route('variation.update', $variation_details->id) }}" method="POST" id="variationDetailForm" name="variationDetailForm">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <input value="{{$variation_details->var_option_name}}" type="text" name="var_option_name" id="var_option_name" class="form-control" placeholder="Option Name">
                            <p class="error"></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <input value="{{$variation_details->code}}" type="text" name="code" id="code" class="form-control" placeholder="Code">
                            <p class="error"></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update</button>
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
    $("#variationDetailForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: element.attr('action'),
            type: 'POST',
            data: element.serialize(),
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);

                if(response.status == true){
                    window.location.href="{{route('variation.index')}}";
                } else {
                    var errors = response.errors;
                    $(".error").removeClass('is-invalid').html('');
                    $("input[type='text']").removeClass('is-invalid');
                    $.each(errors, function(key, value) {
                        $("#" + key).addClass('is-invalid');
                        $("#" + key).siblings('.error').addClass('invalid-feedback').html(value);
                    });
                }
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong");
            }
        });
    });
</script>
@endsection
