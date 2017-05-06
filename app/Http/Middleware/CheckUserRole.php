<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // dd(auth()->user());
        $user = auth()->user();
        //check user roles, if not break
        if (!$user->hasRole($role)){
            dd('Kamu  bukan admin, tak boleh akses');
        }
        return $next($request);
    }
}
