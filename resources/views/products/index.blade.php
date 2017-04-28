@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 {{-- col-md-offset-2 --}}">
            <div class="panel panel-info">
                <div class="panel-heading">Manage Products</div>
                
                <div class="panel-body">
                    <a href="{{route('products.create')}}" class="btn btn-info pull-right">Create product</a>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Product Title</th>
                                <th>Brand</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>category</th>
                                <th>Location</th>
                                <th>Condition</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->product_name}}</td>

                                <td>{{$product->brand->brand_name}}</td>
                                
                                <td>{{$product->product_description}}</td>
                                <td>{{$product->product_price}}</td>
                                <td>{{$product->subcategory_id}}</td>
                                <td>{{$product->area_id}}</td>
                                <td>{{$product->condition}}</td>
                                
                                
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
