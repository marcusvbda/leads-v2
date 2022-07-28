<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Auth;

class RootAuth extends Middleware
{

    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $user = Auth::user();
            if (!$user->isSuperAdmin()) {
                return $this->unauthenticated($request, $guards);
            }
            return $next($request);
        } catch (\Exception $e) {
            return $this->unauthenticated($request, $guards);
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return secure_url(route('login', ["redirect" => request()->path()]));
        }
    }
}
