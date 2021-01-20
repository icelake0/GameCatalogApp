<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreApiRequest
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
        $request->merge(['X-Start-Time' => microtime(true)]);
        return $next($request);
    }
}
