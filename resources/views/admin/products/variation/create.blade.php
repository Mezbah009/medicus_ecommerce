@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Pages</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('pages.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <form action="" method="GET">
{{--                <form id="VariationForm" method="GET" enctype="multipart/form-data">--}}

                <div class="card-header">
                    {{-- <div class="card-title">
                        <button type="button" onclick ="window.location.href='{{route("pages.index")}}'" class="btn btn-default btn-sm">reset</button>
                    </div> --}}
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="text" name="keyword" value="{{Request::get('keyword')}}" class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            @if ($variation->isNotEmpty())
                                @foreach ($variation as $variations)
                                    <th>{{$variations->var_name}}</th>
                                @endforeach
                            @endif
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="dropdownRows">
                        <tr>
                            @if (!empty($color))
                                <td>
                                    <select id="colorSelect" name="arr[0][var_option_color]" class="form-control">
                                        <option value="">Select a Variation</option>
                                        @if($color->isNotEmpty())
                                            @foreach($color as $colors)
                                                <option value="{{$colors->id}}">{{$colors->var_option_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            @endif
                            @if (!empty($size))
                                <td>
                                    <select id="sizeSelect" name="arr[0][var_option_size]" class="form-control">
                                        <option value="">Select a Variation</option>
                                        @if($size->isNotEmpty())
                                            @foreach($size as $sizes)
                                                <option value="{{$sizes->id}}">{{$sizes->var_option_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            @endif
                            <td>
                                <div class="col-4 form-group">
                                    <div class="col-md-12">
                                        <button type="button" name="add" class="form-control btn btn-secondary" id="addRangeButton">Add</button>
                                    </div>
                                </div>
                            </td>


                        </tr>
                    </tbody>
                </table>

                <div class="col-4 form-group">
                    <div class="col-md-12">

                    <button type="button" name="generate" class="form-control btn btn-secondary" id="addGenerate">generate</button>
                    </div>
                </div>
                <form action="" method="post" id="VariationForm" name="VariationForm">
                <div id="bonus_range_area"></div>
                <div class="mb-3">
                    <input type="hidden" min="0" name="product_id" id="product_id" class="form-control"  value="{{$product_id}}">
                    <p class="error"></p>
                </div>
            </div>
            <div class="col-4 form-group">
                    <div class="col-md-12">

                    <button type="submit"class="form-control btn btn-secondary" >sent</button>
                    </div>
            </form>

            <div class="card-footer clearfix">
                {{-- {{$pages->links()}} --}}

            </div>
        </div>
    </div>
    <!-- /.card -->
    </div>
</section>

<!-- /.content -->
@endsection
@section('customJs')
<script>




        $(document).ready(function(){
        var i = 0;
        var color = {!! json_encode($color) !!};
        var size = {!! json_encode($size) !!};


        $('#addRangeButton').click(function(){
            ++i;

            var newRow = '<tr>' +
                '<td>' +
                    '<select class="form-control" name="arr[' + i + '][var_option_color]">'  +
                        '<option value="">Select Color</option>';
            $.each(color, function(index, item) {
                newRow += '<option value="' + item.id + '">' + item.var_option_name + '</option>';
            });
            newRow += '</select>' +
                '</td>' +

                '<td>' +
                    '<select class="form-control" name="arr[' + i + '][var_option_size]">'  +
                        '<option value="">Select size</option>';
            $.each(size, function(index, itemsize) {
                newRow += '<option value="' + itemsize.id + '">' + itemsize.var_option_name + '</option>';
            });
            newRow += '</select>' + '</td>'+

            '<td>'+

             '<div class="form-group col-4">' +
                '<div class="col-md-12 "><input type="button" value="close" class="closeRangeButton form-control btn btn-warning" ></div>' +
                '</div>' +
                '</td>' +
                '</tr>' ;


            $('#dropdownRows').append(newRow);

        });

        $(document).on('click', '.closeRangeButton', function() {
            $(this).closest('tr').remove();
        });
$(document).ready(function() {
    $('#addGenerate').click(function() {
        var variations = [];
        var colorArr = [];
        var sizeArr = [];

        // Loop through each row in the table body
        $('#dropdownRows tr').each(function(index, row) {
            var color = $(row).find('select[name^="arr"][name$="[var_option_color]"]').val();
            var size = $(row).find('select[name^="arr"][name$="[var_option_size]"]').val();

            // If both color and size are selected, add them to the variations array
            if (color !== '' && size !== '') {
                var existsColor = colorArr.some(function(item) {
                  return item.color === color;
                   });
                   var existsSize = sizeArr.some(function(item) {
                  return item.size === size;
                   });
                   if(!existsColor && color !== '' ){
                colorArr.push({color: color});

                   }
                   if(!existsSize &&  size !== ''){
                sizeArr.push({size: size});

                   }


            } if(color == '' || size == ''){
                if(color == ''){
                color = 'null';
                }
                if(size == ''){
                size = 'null';
                }
                var existsColor = colorArr.some(function(item) {
                  return item.color === color;
                   });
                   var existsSize = sizeArr.some(function(item) {
                  return item.size === size;
                   });
                if(color=='null' && !existsColor){
                    colorArr.push({color: color});
                   }
                   else if(color !=='null' && !existsColor){
                    colorArr.push({color: color});

                }
                   if(size=='null' && !existsSize){
                    sizeArr.push({size: size});
                }else if(size !=='null' && !existsSize){
                    sizeArr.push({size: size});

                }

            }

        });

        console.log('color: ', colorArr);
        console.log('size: ', sizeArr);
       var i = 0;
        $.each(colorArr, function(index, item) {
                $.each(sizeArr, function(indexs, items) {

                    var color = {!! json_encode($color) !!};
                    var size = {!! json_encode($size) !!};
                    console.log('the colors:',color);
                    var colorName = [] ;
                    var sizeCode = [] ;

                  $.each(color, function(indexes, itemes) {

                    if(item.color == itemes.id){
                        console.log('colorname in loop:', itemes.var_option_name);
                        colorName.push({var_option_name: itemes.var_option_name});
                        colorName.push({code: itemes.code});
                    return false;
                    }
                    else if(item.color =='null'){

                        colorName.push({var_option_name: 'null'});
                        colorName.push({code: 'null'});
                        console.log('colorname in loop:',colorName);
                    }
                  });
                  $.each(size, function(indexes, itemes) {
                    if(items.size == itemes.id){
                        console.log('sizeCode in loop:', itemes.var_option_name);
                        sizeCode.push({var_option_name: itemes.var_option_name});
                        sizeCode.push({code: itemes.code});
                    return false;
                    }else if(items.size =='null'){

                       sizeCode.push({var_option_name: 'null'});
                       sizeCode.push({code: 'null'});
                       console.log('sizeCode in loop:',sizeCode);
                   }
                  });
                    ++i;

        $('#bonus_range_area').append('<div class="row md-12">' +
            '<input type="hidden" placeholder="Price" name="arr[' + i + '][var_color_id]" value="'+item.color+'" class="form-control" readonly>' +
            '<input type="hidden" placeholder="Price" name="arr[' + i + '][var_size_id]" value="'+items.size+'" class="form-control" readonly>'+

            '<div class="form-group col-1"><div class="col-md-12 "><input type="text" placeholder="Name"  class="form-control" name="arr[' + i + '][var_color]" value="'+colorName[0].var_option_name+'" readonly></div></div>' +
            '<div class="form-group col-1"><div class="col-md-12 "><input type="text" placeholder="Name"  class="form-control" name="arr[' + i + '][var_size]"  value="'+sizeCode[0].var_option_name+'" readonly></div></div>' +
            '<input type="hidden" placeholder="Price" name="arr[' + i + '][var_code_color]" value="'+colorName[1].code+'" class="form-control" readonly>' +
            '<input type="hidden" placeholder="Price" name="arr[' + i + '][var_code_size]" value="'+sizeCode[1].code+'" class="form-control" readonly>'+

            '<div class="form-group col-1"><div class="col-md-12"><input type="text" placeholder="Price" name="arr[' + i + '][price]"  class="form-control"></div></div>' +

            '<div class="form-group col-1"><div class="col-md-12"><input type="text" placeholder="Compare Price" name="arr[' + i + '][compare_price]" class="form-control"></div></div>' +

            '<div class="form-group col-1"><div class="col-md-12"><input type="text" placeholder="Quantity" name="arr[' + i + '][quantity]" class="form-control"></div></div>' +

            '<div class="form-group col-1"><div class="col-md-12"><input type="text" placeholder="SKU" name="arr[' + i + '][sku]" class="form-control"></div></div>' +

            '<div class="form-group col-1">' +
            '<div class="col-md-12">' +
            '<select name="arr[' + i + '][status]" class="form-control">' +
            '<option value="1">Yes</option>' +
            '<option value="0">No</option>' +
            '</select>' +
            '</div>' +
            '</div>' +

            '<div class="col-md-3">' +
            '<input type="file" id="imageUpload" name="arr[' + i + '][image][]" multiple>' +

                    // '<input type="file" id="imageUpload" name="arr[' + i + '][image][]" multiple>' +
            '</div>' +

            '<div class="form-group col-1.5"><div class="col-md-12 "><input type="button" value="close" class="closeRangeButton form-control btn btn-warning" ></div></div>'+
            '</div>');
           console.log(items.size);

                });
            });

    });
});

    });
    $(document).on('click', '.closeRangeButton', function() {
    $(this).closest('.row').remove();
});

        $('#VariationForm').on('submit', function (e) {
            e.preventDefault();


            var formData = new FormData($(this)[0]);
            var files = $('#imageUpload')[0].files;

            for (var i = 0; i < files.length; i++) {

                // Extract values from form fields
                var colorId = $('[name="arr[' + i + '][var_option_color]"]').val() || 'Null';
                var color = $('[name="arr[' + i + '][var_color]"]').val() || 'Null';
                var colorCode = $('[name="arr[' + i + '][var_code_color]"]').val() || 'Null';
                var size = $('[name="arr[' + i + '][var_size]"]').val() || 'Null';
                var sizeId = $('[name="arr[' + i + '][var_option_size]"]').val() || 'Null';
                var sizeCode = $('[name="arr[' + i + '][var_code_size]"]').val() || 'Null';
                var comparePrice = $('[name="arr[' + i + '][compare_price]"]').val() || 0;
                var price = $('[name="arr[' + i + '][price]"]').val() || 0;
                var quantity = $('[name="arr[' + i + '][quantity]"]').val() || 0;
                var sku = $('[name="arr[' + i + '][sku]"]').val() || 'default_sku';
                var status = $('[name="arr[' + i + '][status]"]').val() || 0;


                // Append values to FormData object
                formData.append('arr[' + i + '][var_color_id]', colorId);
                formData.append('arr[' + i + '][var_color]', color);
                formData.append('arr[' + i + '][var_code_color]', colorCode);
                formData.append('arr[' + i + '][var_size]', size);
                formData.append('arr[' + i + '][var_size_id]', sizeId);
                formData.append('arr[' + i + '][var_code_size]', sizeCode);
                formData.append('arr[' + i + '][compare_price]', comparePrice);
                formData.append('arr[' + i + '][price]', price);
                formData.append('arr[' + i + '][quantity]', quantity);
                formData.append('arr[' + i + '][sku]', sku);
                formData.append('arr[' + i + '][status]', status);
                formData.append('arr[' + i + '][image][]', files[i]);

            }

        $.ajax({
                type: 'POST',
                url: '{{ route("productvariation.store") }}',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                enctype: 'multipart/form-data',

                success: function(response) {
                    console.log('Server Response:', response);

                    if (response.status == true) {
                        window.location.href = '';
                    } else {
                        // Your existing code for handling errors...
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                }
            });
        });

</script>

@endsection
