<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PostApiRequest
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
        $response = $next($request);
        $X_Start_Time = $request['X-Start-Time'];
        $X_End_Time = microtime(true);
        $X_Runtime = (($X_End_Time -  $X_Start_Time)/1000000)."Sec";
        $X_Memory_Used = memory_get_usage() . "Bytes";
        $response->header('X-Start-Time', $X_Start_Time);
        $response->header('X-End-Time', $X_End_Time);
        $response->header('X-Runtime', $X_Runtime);
        $response->header('X-Memory-Used',  $X_Memory_Used);
        return $response;
    }
}
