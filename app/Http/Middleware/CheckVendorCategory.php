<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckVendorCategory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure  $expectedCategory,  $next): Response
    {
        $vendor = auth('vendor')->user();
        if($vendor && $vendor->category !== $expectedCategory){
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}
