@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 {{-- col-md-offset-2 --}}">

        <div class="panel panel-primary">
            <div class="panel-heading">
                Search product
            </div>
            <div class="panel-body">
                <form action="{{ route('products.index') }}" method="GET" >
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!!Form::label('search_state', 'State');!!}
                            {!!Form::select('search_state', $states, null, ['placeholder' => 'Select State','class'=>'form-control','id'=>'state_id']);!!}
                        </div>

                    </div>
                    <div class="col-md-3">
                    
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!!Form::label('search_anything', 'Search Anything');!!}
                            {!! Form::text('search_anything',Request::get('search_anything'),['class'=>'form-control']); !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        

                    
                    </div>

                    <div class="col-md-1" style="padding-top: 27px;">
                    <button type="submit" class="btn btn-success">search</button>
                    </div>



                </div>

                
                   
                
                    






                </form>
                
            </div>
            
        </div>



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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->product_name}}</td>

                                <td>{{$product->brand->brand_name}}</td>
                                
                                <td>{{$product->product_description}}</td>
                                <td>{{$product->product_price}}</td>
                                <td>{{$product->subcategory->subcategory_name}}</td>
                                <td>{{$product->area->area_name}}</td>
                                <td>{{$product->condition}}</td>
                                <td><a href="{{ route('products.edit', $product->id)}}" class="btn btn-info">Edit</a></td>
                                
                                
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{-- pagination link --}}
                    {{ $products->appends(Request::except('page'))->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
