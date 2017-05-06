<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Brand;
use App\State;
use App\Category;
use App\Subcategory;
use App\Area;
use App\User;
use App\Auth;
use App\Http\Requests\CreateProductRequest;
use Alert;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(){
        //check dah login
        $this->middleware('auth')->except('index','getCategorySubcategories','getStateAreas','show');
        //check user role
        $this->middleware('check_user_role:members')->except('index','getCategorySubcategories','getStateAreas','show');
        //check product ownership
        $this->middleware('check_product_ownership')->only('edit','destroy','update');

    }
    public function index(Request $request)
    {
        //eager loading relationship to prevent multiple DB request
        $products = Product::with('brand', 'subcategory','area','user');
        //search or
        if (!empty($request->search_anything)){

            $search_anything = $request->search_anything;

            $products = $products->where(function($query) use ($search_anything){
                $query->orWhere('product_name','like','%'.$search_anything.'%')
                        ->orWhere('product_description','like','%'.$search_anything.'%');

            });
        }
        //search by states
        if (!empty($request->search_state)){

            $search_state = $request->search_state;

            $products = $products->whereHas('area', function($query) use ($search_state){
                $query->Where('state_id',$search_state);

            });
        }

        //search by categories
        if (!empty($request->search_category)){

            $search_category = $request->search_category;

            $products = $products->whereHas('subcategory', function($query) use ($search_category){
                $query->Where('category_id',$search_category);

            });
        }

        //search by brand
        if (!empty($request->search_brand)){

            $search_brand = $request->search_brand;

            $products = $products->whereBrandId($search_brand);

            
        }

        //sort by latest product, default ascending tukar jadi descending
        $products = $products->orderBy('id','desc');

        //paginate the data
        $products = $products->paginate(3);

        $brands = Brand::pluck('brand_name','id');
        $categories = Category::pluck('category_name','id');
        $states = State::pluck('state_name','id');
        
        return view('products.index', compact('areas', 'products','brands','categories','states'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::pluck('state_name','id');
        $brands = Brand::pluck('brand_name','id');


        $areas = Area::pluck('area_name','id');
        $categories = Category::pluck('category_name','id');

        return view('products.create',compact('brands','states','categories','areas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        //
        $product = new Product;
        $product->product_name = $request->product_name;
        $product->product_description = $request->product_description;
        $product->product_price = $request->product_price;
        $product->brand_id = $request->brand_id;
        $product->area_id = $request->area_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->condition = $request->condition;

        // cara dapatkan current user ID
        $product->user_id = auth()->id();

        //upload image
        if ($request->hasFile('product_image')){
            $path = $request->product_image->store('public/uploads');

            //save
            $product->product_image = $request->product_image->hashName();
        }

        $product->save();
        //set success message
        //flash('Product successfully inserted')->success();
        // flash()->overlay('Product successfully inserted!', 'Success');
        Alert::success('Products Successfully Saved');


        //kembali ke senarai product
        return redirect()->route('my_products');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //dapatakan maklumat product
        $product = Product::find($id);

        $states = State::pluck('state_name','id');
        $brands = Brand::pluck('brand_name','id');


        // $areas = Area::pluck('area_name','id');
        $categories = Category::pluck('category_name','id');
        //get area based on previous state
        $areas = $this->getStateAreas($product->area->state_id);
        $subcategory = $this->getCategorySubcategories($product->subcategory->category_id);

        return view('products.show',compact('brands','states','categories','areas','product','subcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //dapatakan maklumat product
        $product = Product::find($id);

        $states = State::pluck('state_name','id');
        $brands = Brand::pluck('brand_name','id');


        // $areas = Area::pluck('area_name','id');
        $categories = Category::pluck('category_name','id');
        //get area based on previous state
        $areas = $this->getStateAreas($product->area->state_id);
        $subcategory = $this->getCategorySubcategories($product->subcategory->category_id);

        return view('products.edit',compact('brands','states','categories','areas','product','subcategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->product_name = $request->product_name;
        $product->product_description = $request->product_description;
        $product->product_price = $request->product_price;
        $product->brand_id = $request->brand_id;
        $product->area_id = $request->area_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->condition = $request->condition;

        // cara dapatkan current user ID
        // $product->user_id = auth()->id();

        //upload image
        if ($request->hasFile('product_image')){
            $path = $request->product_image->store('public/uploads');

            //save
            $product->product_image = $request->product_image->hashName();


        }

        $product->save();
        //set success message
        // flash('Product successfully updated')->success();
        flash()->overlay('Product successfully updated!', 'Success');

        //kembali ke senarai product
        return redirect()->route('my_products',$product->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete product
        $product = Product::findOrFail($id);
        $product->delete();
        // flash('Product successfully deleted')->success();
        Alert::success('Product deleted!');
        return redirect()->route('products.index');


    }

    public function getStateAreas($state_id)
    {
        // echo 'dah sampai, ni state id'.$state_id;
        $areas = Area::whereStateId($state_id)->pluck('area_name','id');
        return $areas;
    }

    public function getCategorySubcategories($category_id)
    {
        $subcategories = Subcategory::whereCategoryId($category_id)->pluck('subcategory_name','id');
        return $subcategories;
    }

    public function my_products(Request $request)
    {
        // dapatakan user tengah login
        $user = auth()->user();

        //eager loading relationship to prevent multiple DB request
        // $products = Product::with('brand', 'subcategory','area','user'); //asal papar semua
        $products = $user->products()->with('brand', 'subcategory','area','user'); //papar hanya product untuk user tgh login
        //search or
        if (!empty($request->search_anything)){

            $search_anything = $request->search_anything;

            $products = $products->where(function($query) use ($search_anything){
                $query->orWhere('product_name','like','%'.$search_anything.'%')
                        ->orWhere('product_description','like','%'.$search_anything.'%');

            });
        }
        //search by states
        if (!empty($request->search_state)){

            $search_state = $request->search_state;

            $products = $products->whereHas('area', function($query) use ($search_state){
                $query->Where('state_id',$search_state);

            });
        }

        //search by categories
        if (!empty($request->search_category)){

            $search_category = $request->search_category;

            $products = $products->whereHas('subcategory', function($query) use ($search_category){
                $query->Where('category_id',$search_category);

            });
        }

        //search by brand
        if (!empty($request->search_brand)){

            $search_brand = $request->search_brand;

            $products = $products->whereBrandId($search_brand);

            
        }

        //sort by latest product, default ascending tukar jadi descending
        $products = $products->orderBy('id','desc');

        //paginate the data
        $products = $products->paginate(3);

        $brands = Brand::pluck('brand_name','id');
        $categories = Category::pluck('category_name','id');
        $states = State::pluck('state_name','id');
        
        return view('products.my_products', compact('areas', 'products','brands','categories','states'));

    }
}
