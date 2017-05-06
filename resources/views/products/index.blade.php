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
                    <div class="col-md-6">
                        <div class="form-group">
                            {!!Form::label('search_anything', 'Search Anything');!!}
                            {!! Form::text('search_anything',Request::get('search_anything'),['class'=>'form-control']); !!}
                        </div>
                    </div>

                    
                    <div class="col-md-2" style="padding-top: 27px;">
                        <button type="submit" class="btn btn-success">search</button>
                    </div>
                    
                    
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!!Form::label('search_state', 'State');!!}
                            {!!Form::select('search_state', $states, Request::get('search_state'), ['placeholder' => 'Select State','class'=>'form-control','id'=>'state_id']);!!}
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!!Form::label('search_area', 'Area');!!}
                            {!!Form::select('search_area', [], Request::get('search_area'), ['placeholder' => 'Select Area','class'=>'form-control','id'=>'area_id']);!!}
                        </div>
                        
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!!Form::label('search_brand', 'Brand');!!}
                            {!!Form::select('search_brand', $brands, Request::get('search_brand'), ['placeholder' => 'Select brand','class'=>'form-control','id'=>'brand_id']);!!}
                        </div>

                        
                    </div>
                    <div class="col-md-3">
                        

                    
                    </div>

                    



                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">

                            {!! Form::label('search_category', 'Category') !!}   
                            {!! Form::select('search_category', $categories, Request::get('search_category'), ['placeholder' => 'Select Category','class'=>'form-control','id'=>'category_id']); !!}
                        </div>
                        

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('search_subcategory', 'Subcategory') !!}   
                            {!! Form::select('search_subcategory', [], Request::get('search_subcategory'), ['placeholder' => 'Select subcategory','class'=>'form-control','id'=>'subcategory_id']); !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        
                    </div>
                    <div class="col-md-3">
                        

                    
                    </div>
                    

                </div>
                <div class="row">
                    
                    
                </div>
                

                
                   
                
                    






                </form>
                
            </div>
            
        </div>



            <div class="panel panel-info">
                <div class="panel-heading">List all Products</div>
                
                <div class="panel-body">
                        @role('members')
                            <div class="form-group" >
                                <a href="{{route('products.create')}}" class="btn btn-info pull-right" style="margin-bottom: 10px">Create product</a>
                            </div>
                        @endrole
                        
                    
                        
                    
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width:150px;">Thumbnail</th>
                                    <th>Product Title</th>
                                    <th>Brand</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Subcategory</th>
                                    <th>Location</th>
                                    <th>Condition</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                    @if(!empty($product->product_image))
                                        <img src="{{ asset('storage/uploads/'.$product->product_image) }}" class="img-thumbnail">
                                    @else
                                        <img src="{{ asset('storage/uploads/NoThumbnail.png') }}" class="img-thumbnail"/>
                                    @endif
                                    </td>

                                    
                                    <td>{{$product->product_name}}</td>

                                    <td>{{$product->brand->brand_name}}</td>
                                    
                                    <td>{{$product->product_description}}</td>
                                    <td>{{$product->product_price}}</td>
                                    <td>{{$product->subcategory->subcategory_name}}</td>
                                    <td>{{$product->area->area_name}}</td>
                                    <td>{{$product->condition}}</td>
                                    <td><a href="{{ route('products.show', $product->id)}}" class="btn btn-info">Show</a></td>
                                    {{-- Buang sebab dah ada dalam My_products.blade.php --}}
                                    {{-- <td>
                                    
                                    <form method="POST" action="{{ route('products.destroy',$product->id) }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        {{ csrf_field() }}
                                        <a href="{{ route('products.edit', $product->id)}}" class="btn btn-info">Edit</a>
                                        <button type="button" class="btn btn-warning delete">Delete</button>
                                        
                                    </form>

                                    </td> --}}
                                    
                                    
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

@section('script')
<script type="text/javascript">
$( document ).ready(function() {
    //Bila pengguna select state
    $( "#state_id" ).change(function() {
      
      //dapatkan value state yang kita pilih
      var state_id = $(this).val();

      getStateAreas(state_id);
    
    });
    //bila pengguna click pada pagination, reload balik areas based on selected state
    var selected_state_id = '{{ Request::get('search_state') }}';

    //bila pengguna click pada pagination, reload balik areas based on selected state
    if (selected_state_id.length>0){
        getStateAreas(selected_state_id);
    };

    //ajax function get areas by state copy dari create .blade
    function getStateAreas(state_id){

      //define route untuk hantar id state ke controller, grab data area

      var ajax_url = '/products/areas/' + state_id;

      //dapatkan areas data dari Controller menggunakan Ajax

      $.get( ajax_url, function( data ) {
        
        // dah dapat data, kosongkan dulu dropdown area dan tambah Select Area

        $('#area_id').empty().append('<option value="">Select Area</option');

        // loop data untuk hasilkan senarai option baru bagi dropdown

        $.each(data, function(area_id,area_name){
        
            $('#area_id').append('<option value='+area_id+'>'+area_name+'</option');
        });

        //dapatkan previous selected area if there is form validation error
        var selected_area_id = '{{ Request::get('search_area') }}';

        //

        if (selected_area_id.length>0) {
            //pilih balik area based on previous selected are
            $('#area_id').val(selected_area_id);
        };

      });


    }

    //Bila pengguna select category
    $( "#category_id" ).change(function() {
      
      //dapatkan value state yang kita pilih
      var category_id = $(this).val();

      getCategorySubcategories(category_id);
    
    });

    //dapat balik category bila pagination
    var selected_category_id = '{{ Request::get('search_category') }}';

    //dapat balik subcategory bila pagination
    if (selected_category_id.length>0) {
        
        getCategorySubcategories(selected_category_id);

    };

    function getCategorySubcategories(category_id){

        //hantar id state ke controller, grab data area

        var ajax_url = '/products/subcategories/' + category_id;

        $.get( ajax_url, function( data ) {
          
          // console.log(data);

          $('#subcategory_id').empty().append('<option value="">Select Subcategory</option');

          $.each(data, function(subcategory_id,subcategory_name){
          
              $('#subcategory_id').append('<option value='+subcategory_id+'>'+subcategory_name+'</option');
          });

          //dapatkan previous selected subcategory if there is form validation error
          var selected_subcategory_id = '{{ Request::get('search_subcategory') }}';

          if (selected_subcategory_id.length>0) {
              //pilih balik subcategory based on previous selected are
              $('#subcategory_id').val(selected_subcategory_id);
          };

        });

    }
    // bila pengguna click butang delete
    $('.delete').click(function(){

        //Dapatkan butang yang terdekatdengan butang delete yang kita tekan
        var closest_form = $(this).closest('form');

        swal({
          title: "Are you sure bro?",
          text: "You will not be able to recover this product!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, delete it!",
          closeOnConfirm: false
        },
        function(){
            closest_form.submit();
          //swal("Deleted!", "Your Product has been deleted.", "success");

        });
    });
});
    

</script>


@endsection
