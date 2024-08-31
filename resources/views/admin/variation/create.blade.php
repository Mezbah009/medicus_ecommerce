@extends('admin.layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Product Variatons</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('variation.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
	<form action="{{ route('variation.store') }}" method="post" id="VariationForm" name="VariationForm">
    @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="var_name" class="form-label">Variation Name</label>
                            <input type="text" name="var_name" id="var_name" class="form-control" placeholder="Variation Name">
                            <p class="error"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="name" class="col-md-12 col-form-label">Additional Information</label>
                            <div class="row">

                            <!-- Variation option name field  -->
                            <div class="col-4 form-group">
                                    <div class="col-md-12">
                                        <input class="form-control @error('var_option_name') is-invalid @enderror" placeholder="Name" id="var_option_name" name="arr[0][var_option_name]" type="text" value="{{ old('var_option_name') }}">
                                        <p class="error"></p>
                                        @error('var_option_name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Variation option code field  -->
                                <div class="col-6 form-group">
                                    <div class="col-md-12">
                                        <input class="form-control @error('code') is-invalid @enderror" placeholder="Code" id="code" name="arr[0][code]" type="text" value="{{ old('code') }}">
                                        <p class="error"></p>
                                        @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-2 form-group">
                                    <div class="col-md-12">
                                        <input type="button" name="add" value="add" class="form-control btn btn-secondary" id="addRangeButton">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="bonus_range_area"></div>
                </div>
                
            </div>
        </div>
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{route('variation.create')}}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
        </form>
    </div>
</section>
@endsection

@section('customJs')
<script>
$(document).ready(function(){
    var i = 0;
    $('#addRangeButton').click(function(){
        ++i;
        $('#bonus_range_area').append('<div class="row">' +
            '<div class="form-group col-4"><div class="col-md-12 "><input type="text" placeholder="Name" name="arr['+i+'][var_option_name]" class="form-control"></div></div>' +
            '<div class="form-group col-6"><div class="col-md-12 "><input type="text" placeholder="Code" name="arr['+i+'][code]" class="form-control"></div></div>' +
            '<div class="form-group col-2"><div class="col-md-12 "><input type="button" value="close" class="closeRangeButton form-control btn btn-warning" ></div></div>'+
            '</div>');
    });

    $('#VariationForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this);

        $.ajax({
            type: 'POST',
            url: '{{ route("variation.store") }}', 
            data: formData.serialize(), // Changed to serialize()
            dataType: 'json', // Added quotes around 'json'

            success: function(response) {  // Fixed the typo
            if(response.status == true){  // Changed response["status"] to response.status
                window.location.href='{{route("variation.index")}}';

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
});

$(document).on('click', '.closeRangeButton', function() {
    $(this).closest('.row').remove();
});
</script>

@endsection
