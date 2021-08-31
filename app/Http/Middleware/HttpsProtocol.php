<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;

class HttpsProtocol
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
        if (!$request->secure() && config('app.env') != 'local') {
            URL::forceScheme('https');
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
