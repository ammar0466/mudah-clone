<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'ProductsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::resource('states', 'StatesController');
Route::resource('areas', 'AreasController');
Route::resource('categories', 'CategoriesController');
Route::resource('subcategories', 'SubcategoriesController');
Route::resource('brands', 'BrandsController');
Route::resource('listingtype', 'ListingtypesController');

//route for products
Route::get('my_products', 'ProductsController@my_products')->name('my_products');
Route::resource('products', 'ProductsController');
Route::resource('products.create', 'ProductsController');
Route::get('products/areas/{states_id}', 'ProductsController@getStateAreas');
Route::get('products/subcategories/{category_id}', 'ProductsController@getCategorySubcategories');

//route for admin
Route::group(['prefix' => 'admin','as'=>'admin.'], function (){
	//route for products
	Route::get('products/areas/{states_id}', 'Admin\AdminProductsController@getStateAreas');
	Route::get('products/subcategories/{category_id}', 'Admin\AdminProductsController@getCategorySubcategories');
	Route::resource('products', 'Admin\AdminProductsController');

});




