@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Variation Option Details</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href='{{route("variation.add", $id)}}' class="btn btn-primary">Add</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form action="" method="GET">
                @csrf
                <div class="card-header">
                    @if($variation_details->isNotEmpty())
                    <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route("variation.show", $id) }}'" class="btn btn-default btn-sm">Reset</button>
                    </div> 
                    @endif
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="text" name="keyword" value="{{ Request::get('keyword') }}" class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Variation Options</th>
                            <th>Codes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($variation_details->isNotEmpty())
                            @foreach($variation_details as $detail)
                                <tr>
                                    <td>
                                        {{ $detail->var_option_name }}
                                    </td>
                                    <td>
                                        {{ $detail->code }}
                                    </td>
                                    <td>
                                        <a href="{{ route('variation.edit', $detail->id)}}">
                                            <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                        </a>
                                        <a href="#" onclick="deleteVartiationDetails({{ $detail->id}})" class="text-danger w-4 h-4 mr-1">

                                            <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No variation details available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pb-5 pt-3">
                    <a href='{{route("variation.index")}}' class="btn btn-outline-dark ml-3">Back</a>
		</div>
    </div>
</section>
@endsection
@section('customJs')
<script>
    function deleteVartiationDetails(id){
         var url = '{{ route("variation.delete","ID")}}';
         var newURL = url.replace("ID",id);

         if(confirm("Are you sure you want to delete")){
            $.ajax({
            url: newURL,
            type: 'delete',
            data: {},
            dataType: 'json', // Use lowercase for 'json'
            headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response["status"]) { // Simplify if condition
                window.location.href="{{route('variation.show',$id)}}"
                }
            }
        });

         }
    }
</script>
@endsection
