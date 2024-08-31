@extends('admin.layouts.app')
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Brand</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('brands.create') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="{{ route('brands.store') }}" id="editBrandForm" name="editBrandForm" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $brand->name }}">
                                    <p class="error"></p> <!-- Corrected opening tag <p> -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug"value="{{ $brand->slug }}" >
                                    <p class="error"></p> <!-- Corrected opening tag <p> -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ ($brand->status == 1) ? 'selected' : '' }} value="1">Active</option>
                                        <option {{ ($brand->status == 0) ? 'selected' : '' }}  value="0">Block</option>
                                    </select>
                                    <p class="error"></p> <!-- Corrected opening tag <p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('brands.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')


<script>
    $("#editBrandForm").submit(function (event) {
        event.preventDefault();
        var formArray = $(this).serializeArray();

        // Add the CSRF token to the form data
        formArray.push({ name: "_token", value: "{{ csrf_token() }}" });

        $.ajax({
            url: '{{ route("brands.update", $brand->id) }}',
            type: 'put',
            data: formArray,
            dataType: 'json',
            success: function (response) {
                if (response["status"] == true) {
                    window.location.href="{{route('brands.index')}}"

                    // Handle success
                } else {

                    if(response['notFound']== true) {
                        window.location.href= "{{ route('brands.index') }}";
                    }



                    var errors = response['errors'];
                    $(".error").removeClass('is-invalid').html(''); // Remove error classes and clear error messages
                    $("input[type='text'], select").removeClass('is-invalid');
                    $.each(errors, function (key, value) {
                        $("#" + key).addClass('is-invalid');
                        $("#" + key).next('p').addClass('invalid-feedback').html(value);
                    });
                }
            },
            error: function (jqXHR, exception) {
                console.log("Something went wrong");
            }
        });
    });

    $("#name").on("input", function () { // Use "input" event for real-time input tracking
        element = $(this);

        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: { title: element.val() },
            dataType: 'json',
            success: function (response) {
                if (response["status"] == true) {
                    $("#slug").val(response["slug"]);
                }
            }
        });
    });
</script>
@endsection
