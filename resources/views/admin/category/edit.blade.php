@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('categories.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <form action="" method="POST" id="categoryForm" name="categoryForm">
        {{-- @csrf --}}

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value={{$category->name}}>
                            <p class="error"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug">Slug</label>
                            <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value={{$category->slug}}>
                            <p class="error"></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-1">
                    <label for="image">Image</label>
                    <input type="hidden" id="image_id" name="image_id">

                      <div id="image" class="dropzone dz-clickable">
                          <div class="dz-message needsclick">
                                <br>Drop files here or click to upload.<br><br>
                            </div>
                        </div>
                        </div>
                        @if (!@empty($category->image))
                        <div>
                            <img width="250" src="{{asset('uploads/category/thumb/'.$category->image)}}" alt="">
                        </div>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <div class="mb-1">
                            <label for="status">status</label>
                            <select  name="status" id="status" class="form-control" >
                                <option {{($category->status ==1)? 'selected' : ''}} value="1">Active</option>
                                <option {{($category->status ==0)? 'selected' : ''}} value="0">Deactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status">Show on Home</label>
                            <select  name="showHome" id="showHome" class="form-control" >
                                <option {{($category->showHome =='Yes')? 'selected' : ''}} value="Yes">Yes</option>
                                <option {{($category->showHome =='No')? 'selected' : ''}} value="No">No</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="order">order</label>
                                <input type="integer" name="order" id="oder" class="form-control" placeholder="1" value={{$category->order}}>

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
    $("#categoryForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled',true);
        $.ajax({
            url: '{{ route("categories.update", $category->id) }}',
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
                    window.location.href="{{route('categories.index')}}"



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

   $("#name").change(function(){
    element = $(this);
    $("button[type=submit]").prop('disabled',true);
    $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: {title: element.val()},  // Fixed typo: 'data' instead of 'date'
            dataType: 'json',
            headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
            success: function(response) {  // Fixed typo: 'function' instead of 'funtion'
                // Handle success response here
                $("button[type=submit]").prop('disabled',false);
                if(response["status"]== true){
                    $("#slug").val(response["slug"])
                }
    }
});
   });


   Dropzone.autoDiscover = false;
const dropzone = $("#image").dropzone({
    init: function() {
        this.on('addedfile', function(file) {
            if (this.files.length > 1) {
                this.removeFile(this.files[0]);
            }
        });
    },
    url:  "{{ route('temp-images.create') }}",
    maxFiles: 1,
    paramName: 'image',
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg,image/png,image/gif",
    headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
     success: function(file, response){
        $("#image_id").val(response.image_id);
        console.log(response)
    }
});

</script>


@endsection
