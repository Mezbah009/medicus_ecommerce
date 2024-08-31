@extends('admin.layouts.app')
@section('content')
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Create Sub Category</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href='{{route("sub-categories.index")}}' class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<div class="container-fluid">
                        <form action="subCategoryForm" id="subCategoryForm">
                        @csrf

						<div class="card">
							<div class="card-body">
								<div class="row">
                                    <div class="col-md-12">
										<div class="mb-3">
											<label for="name">Category</label>
											<select name="category" id="category" class="form-control">
                                                <option value="">Select a Category</option>
                                                @if($categories->isNotempty())
                                                @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <p class="error"></p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="name">Name</label>
											<input type="text" name="name" id="name" class="form-control" placeholder="Name" req>
                                            <p class="error"></p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="Slug">Slug</label>
											<input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
                                            <p class="error"></p>
										</div>
									</div>
                                    <div class="col-md-6">
										<div class="mb-3">
											<label for="status">Status</label>
											<select name="status" id="status" class="form-control">
                                                    <option value="1">Active</option>
                                                    <option value="0">Deactive</option>

                                            </select>
										</div>

                                        <div class="mb-3">
                                            <label for="status">Show on Home</label>
                                            <select  name="showHome" id="showHome" class="form-control" >
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
									</div>
								</div>
							</div>
						</div>
						<div class="pb-5 pt-3">
							<button type="submit" class="btn btn-primary">Create</button>
							<a href='{{route("sub-categories.create")}}' class="btn btn-outline-dark ml-3">Cancel</a>
						</div>
                        </form>
					</div>

					<!-- /.card -->
				</section>
				<!-- /.content -->
@endsection
@section('customJs')
<script>
    $("#subCategoryForm").submit(function(event){
    event.preventDefault();
    var element = $(this);

    // Add the CSRF token to the form data
    element.serializeArray().push({ name: "_token", value: "{{ csrf_token() }}" }); // Fixed the array name

    $.ajax({
        url: '{{ route("sub-categories.store") }}',
        type: 'POST',
        data: element.serialize(), // Fixed the typo
        dataType: 'json',
        success: function(response) {  // Fixed the typo
            if(response.status == true){  // Changed response["status"] to response.status
                window.location.href='{{route("sub-categories.index")}}';

            } else {
                var errors = response.errors;
                $(".error").removeClass('is-invalid').html(''); // Remove error classes and clear error messages
                $("input[type='text'], select").removeClass('is-invalid');
                $.each(errors, function(key, value) {
                    $("#" + key).addClass('is-invalid'); // Use the correct syntax for concatenating strings
                    $("#" + key).next('p').addClass('invalid-feedback').html(value); // Use the correct syntax for concatenating strings
                });
            }
        },
        error: function(jqXHR, exception) {
            console.log("Something went wrong");
        }
    });
});

        $("#name").change(function () { // Use "input" event for real-time input tracking
        element = $(this);
        $("button[type=submit]").prop('disabled', true); // Use prop() to set the 'disabled' property

        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: { title: element.val() },
            dataType: 'json', // Use lowercase for 'json'
            success: function (response) {
                $("button[type=submit]").prop('disabled', false); // Enable the button
                if (response["status"] == true) { // Simplify if condition
                    $("#slug").val(response["slug"]); // Set the value of the 'slug' input
                }
            }
        });
    });


</script>
@endsection
