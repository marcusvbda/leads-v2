<?php

namespace App\Http\Middleware;

use App\Http\Models\UserIntegrator;
use Closure;

class BasicAuthIntegrators
{
    public function handle($request, Closure $next)
    {
        $auth =  str_replace('Basic ', '', $request->header('Authorization'));
        $auth = explode(':', base64_decode($auth));

        $user = UserIntegrator::where("enabled", true)
            ->where("key", @$auth[0])
            ->where("secret", @$auth[1])
            ->first();

        if (!@$user->id) {
            abort(401, 'Unauthorized');
        }

        $request->request->add(['user' => $user]);

        return $next($request);
    }
}
