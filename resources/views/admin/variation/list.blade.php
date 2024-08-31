@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>List of Variations</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('variation.create')}}" class="btn btn-primary">New Variation</a>
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
                <div class="card-title">
                    <button type="button" onclick ="window.location.href='{{route("variation.index")}}'" class="btn btn-default btn-sm">reset</button>
                </div>
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="text" name="keyword"value="{{Request::get('keyword')}}" class="form-control float-right" placeholder="Search">
        
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
                                <th>Variation Name</th>
                                <th>Variation Options</th>
                                <th>Codes</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($variations as $variation)
                                <tr>
                                    <td>{{ $variation->var_name }}</td>
                                    <td>
                                        @foreach($variation->variationDetails as $detail)
                                            {{ $detail->var_option_name }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($variation->variationDetails as $detail)
                                            {{ $detail->code }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href='{{route("variation.show", $variation->id)}}' class="btn btn-info">Show Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
