<?php

namespace App\Http\Middleware;

use App\Models\WebMobileApi as ModelsWebMobileApi;
use Closure;
use Illuminate\Http\Request;

class WebMobileApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $api_key = $request->header('Authorization');

        if ($api_key) {
            $api_key = ModelsWebMobileApi::where('api_key', $api_key)->where('status', 1)->get();

            if ($api_key->isEmpty()) {
                return response()->json('Unauthorized', 401);
            }

            return $next($request);
        } else {
            return response()->json('Unauthorized', 401);
        }
    }
}