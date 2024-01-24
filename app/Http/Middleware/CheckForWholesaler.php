<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForWholesaler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $role = auth()->user()->role;

        if ($role !== 'wholesaler') {
            return redirect('home');
        }

        return $next($request);
    }
}
