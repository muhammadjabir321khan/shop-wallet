<?php

namespace App\Http\Middleware;

use App\Traits\System\ActivationTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class ActivationCheckMiddleware
{
    use ActivationTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $area = null): mixed
    {
       
        return $next($request);
    }
}
