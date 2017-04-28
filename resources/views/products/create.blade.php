@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">Create Products</div>
                
                <div class="panel-body">
                {{-- papar validation error --}}
                @if($errors->all())
                <div class="alert alert-danger" role="alert">
                    <p> Validation Error. Bekki please </p>
                    <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{$message }}</li>
                    @endforeach
                    </ul>
                </div>
                @endif


                


                {{-- tambah form di sini --}}
                
                {!! Form::open(['route' => 'products.store']) !!}
                    <div class="form-group">
                        {!!Form::label('product_name', 'Product Name');!!}
                        {!! Form::text('product_name','',['class'=>'form-control']); !!}
                    </div>

                    <div class="form-group">
                        {!!Form::label('product_description', 'Product Description');!!}
                        {!! Form::textarea('product_description','',['class'=>'form-control']); !!}
                    </div>

                    <div class="form-group">
                        {!!Form::label('product_price', 'Product Price');!!}
                        {!! Form::text('product_price','',['class'=>'form-control']); !!}
                    </div>

                    <div class="form-group">
                        {!!Form::label('condition', 'Condition');!!}
                        {!!Form::radio('condition', 'new', true);!!} New
                        {!!Form::radio('condition', 'used', false);!!} Used
                    </div>

                    <div class="form-group">
                        {!!Form::label('brand_id', 'Brand');!!}
                        {!!Form::select('brand_id', $brands, null, ['placeholder' => 'Select Brand','class'=>'form-control']);!!}
                    </div>

                    <div class="form-group">
                        {!!Form::label('state_id', 'State');!!}
                        {!!Form::select('state_id', $states, null, ['placeholder' => 'Select State','class'=>'form-control','id'=>'state_id']);!!}
                    </div>

                    <div class="form-group">
                        {!!Form::label('area_id', 'Area');!!}
                        {!!Form::select('area_id', $areas, null, ['placeholder' => 'Select Area','class'=>'form-control','id'=>'area_id']);!!}
                    </div>

                    <div class="form-group">
                        {!!Form::label('category_id', 'Category');!!}
                        {!!Form::select('category_id', $categories, null, ['placeholder' => 'Select Category','class'=>'form-control']);!!}
                    </div>

                    <div class="form-group">
                        {!!Form::label('subcategory_id', 'Subcategory');!!}
                        {!!Form::select('subcategory_id', [], null, ['placeholder' => 'Select Subcategory','class'=>'form-control']);!!}
                    </div>




                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>

                        <a href="{{ route('products.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                {!! Form::close() !!}

                
                {{-- tutup form sini --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        $( document ).ready(function() {
            // console.log( "sekarang berada di form create product" );

            $( "#state_id" ).change(function() {

                //dapatkan value state id yang di pilih
                var state_id = $(this).val();
                console.log(state_id);

                //hantar id ke controller
                var ajax_url = '/products/areas/' + state_id;
                $.get( ajax_url, function( data ) {
                // console.log(data);

                $('#area_id').empty().append('<option value="">Select Area</option');

                $.each(data, function(area_id,area_name){
                    // console.log(area_id);
                    // console.log(area_name);
                    $('#area_id').append('<option value='+area_id+'>'+area_name+'</option');
                });
                });

            });

            $( "#category_id" ).change(function() {

                //dapatkan value state id yang di pilih
                var category_id = $(this).val();
                console.log(category_id);

                //hantar id ke controller
                var ajax_url = '/products/subcategories/' + category_id;
                $.get( ajax_url, function( data ) {
                // console.log(data);

                $('#subcategory_id').empty().append('<option value="">Select Subcategory</option');

                $.each(data, function(subcategory_id,subcategory_name){
                    // console.log(subcategory_id);
                    // console.log(subcategory_name);
                    $('#subcategory_id').append('<option value='+subcategory_id+'>'+subcategory_name+'</option');
                });
                });

            });
        });
    </script>
@endsection