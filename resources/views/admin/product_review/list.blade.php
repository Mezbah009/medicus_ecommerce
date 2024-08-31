@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Product Reviews</h1>
            </div>
            <div class="col-sm-6 text-right">
                {{-- <a href="{{ route('product_review.create') }}" class="btn btn-primary">New Brand</a> --}}
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
                        <button type="button" onclick="window.location.href='{{ route("product_review.index") }}'" class="btn btn-default btn-sm">reset</button>
                    </div>
                    <div class="card-tools">
                        <div class="input-group" style="width: 250px;">
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control float-right" placeholder="Search">
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
                            <th>Product Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Comments</th>
                            <th width="120">Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($product_ratings as $product_rating)
                        <tr>
                            <td>{{ $product_rating->product_id }}</td>
                            <td>{{ $product_rating->username }}</td>
                            <td>{{ $product_rating->email }}</td>
                            <td>{{ $product_rating->comment }}</td>

                            <td>
                                <div class="col-md-">
                                    <div class="mb-">

                                        <select name="status" id="status_{{ $product_rating->id }}" class="form-control">
                                            <option value="1" {{ $product_rating->status == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $product_rating->status == 0 ? 'selected' : '' }}>Block</option>
                                        </select>
                                        <p class="error" id="status_error_{{ $product_rating->id }}"></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="#" onclick="toggleStatus({{ $product_rating->id }})" class="text-primary w-4 h-4 mr-1">
                                    Update
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">No product reviews found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $product_ratings->links() }}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    function toggleStatus(id) {
        var statusElement = $('#status_' + id);
        var statusErrorElement = $('#status_error_' + id);

        $.ajax({
            url: '{{ route("product_review.toggle_status") }}',
            type: 'post',
            data: {
                id: id,
                status: statusElement.val(),
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function (response) {
            console.log(response); // Log the response to the console
            if (response["status"]== true) {
                window.location.href = "{{route('product_review.index')}}";
            } else {
                statusErrorElement.text(response.message);
            }
        },

        });
    }
</script>
@endsection
