@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Change Password</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('admin.showChangePassword')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <form action="" method="POST" id="changeForm" name="changeForm">
        {{-- @csrf --}}

    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="name">Old Password</label>
                            <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password">
                            <p class="error"></p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="name">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password">
                            <p class="error"></p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="name">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                            <p class="error"></p>
                        </div>
                    </div>
                       
                  
                    </div>
                </div>
            </div>
        </div>
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary" >update</button>
            <a href="{{route('admin.showChangePassword')}}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
    </div>
</form>

    <!-- /.card -->
</section>
<!-- /.content -->
@endsection
@section('customJs')

    <script>
    $("#changeForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled',true);
        $.ajax({
            url: '{{ route("admin.processChangePassword") }}',
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
                    window.location.href="{{route('admin.showChangePassword')}}"



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
