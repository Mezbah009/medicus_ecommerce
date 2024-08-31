@extends('admin.layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Logo</h1>
                </div>
                <div class="col-sm-6 text-right">
                    @if (empty($logo))
                            <a href="{{ route('logo.create') }}" class="btn btn-primary">New Logo</a>
                    @endif
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
            <div class="card">
                <form action="" method="GET">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick ="window.location.href='{{route("logo.index")}}'" class="btn btn-default btn-sm">reset</button>
                        </div>
                        {{-- <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input type="text" name="keyword" value="{{Request::get('keyword')}}" class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                </form>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>Image</th>
                            <th>Description</th>
                            {{-- <th width="100">Action</th> --}}
                        </tr>
                        </thead>
                        <tbody>
                            @if (!empty($logo))
                                    <tr>
                                        <td>{{$logo->id}}</td>
                                        <td>
                                            @if(!empty($logo->image))
                                                <img src="{{asset('uploads/logo/'.$logo->image)}}" class="img-thumbnail" alt="{{$logo->id}}" width="50">
                                            @else
                                                <img src="{{asset('admin-assets/img/default.png')}}" class="img-thumbnail" alt="default image" width="50">
                                            @endif
                                        </td>
                                        <td>{{$logo->description}}</td>
                                        <td>
                                            <a href="{{route('logo.edit',$logo->id)}}" >
                                                <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                            @else
                            @endif
                            </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{-- {{$logo->links()}} --}}

                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
@section('customJs')
    {{-- <script>
        function destroySlider(id){
            var url = '{{ route("logo.delete", "ID") }}';

            var newUrl  = url.replace("ID",id)
            if (confirm("Are you sure you want to delete")) {
                $.ajax({
                    url: newUrl,
                    type: 'delete',
                    data: {},  // Fixed typo: 'data' instead of 'date'
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {  // Fixed typo: 'function' instead of 'funtion'
                        // Handle success response here
                        $("button[type=submit]").prop('disabled', false);
                        if (response["status"]) {
                            window.location.href = "{{route('logo.index')}}";
                        } else {
                            // Handle other cases if needed
                        }
                    }  // Fixed typo: removed extra closing parenthesis
                });
            }


        }

    </script> --}}
@endsection
