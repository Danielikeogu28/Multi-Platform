<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsVendorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $vendor = Auth::guard('vendor')->user();

       if(Auth::check() && Auth::user()->role !== 'vendor'){
        
        return response()->json(['message' => 'Unauthorized. Vendor role required...'], 403);
            
        }
        // If not a vendor, redirect to home or show an error
        return $next($request);
    }
    
}
