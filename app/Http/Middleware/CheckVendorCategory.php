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
    public function handle(Request $request, Closure $next): Response
    {
        $vendor = auth('vendor')->user();
        if($vendor && optional($vendor->category)->name !== 'E-commerces Vendor'){
            return response()->json(['message' => 'Unauthorized. Category access denied'], 403);
        }

        return $next($request);
    }
}
