<?php

namespace App\Http\Middleware;

use Closure;
use App\Product;

class CheckProductOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //dapatkan product ID dari URL
        $product_id = $request->product;

        // dapatakan produk info based on product id
        $product = Product::find($product_id);

        if ($product){
            //dapatkan user_id untuk product tersebut
            $product_owner = $product->user_id;

            //dapatkan current login user id
            $current_user_id = auth()->id();

            if ($current_user_id != $product_owner){
                //dd("Awak takleh edit ni ore lain pnye");

                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
