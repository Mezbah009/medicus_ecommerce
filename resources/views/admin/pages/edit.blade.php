@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Page</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('pages.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <form action="" method="POST" id="pageForm" name="pageForm">
        {{-- @csrf --}}

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value={{$pages->name}}>
                            <p class="error"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug">Slug</label>
                            <input type="text"   name="slug" id="slug" class="form-control" placeholder="slug" value={{$pages->slug}}>
                            <p class="error"></p>
                        </div>
                    </div>

                   
 
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug">Content</label>
                            <textarea name="content" id="content" class="summernote" cols="30" rows="10" >{{$pages->content}}</textarea>
                            <p class="error"></p>
                        </div>
                    </div>
                   
                    </div>
                </div>
            </div>
        </div>
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary" >Update</button>
            <a href="{{route('pages.create')}}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
    </div>
</form>

    <!-- /.card -->
</section>
<!-- /.content -->
@endsection
@section('customJs')

    <script>
    $("#pageForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled',true);
        $.ajax({
            url: '{{ route("pages.update", $pages->id) }}',
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
                    window.location.href="{{route('pages.index')}}"



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


   

</script>


@endsection