<?php

namespace App\Http\Controllers;

use App\Http\Models\UserIntegrator;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function generateToken(Request $request)
    {
        $this->validateRequestAuth($request);
        $user = UserIntegrator::where("key", $request->key)
            ->where("enabled", true)
            ->where("secret", $request->secret)
            ->firstOrFail();
        $token = $user->generateTokenJWT();
        return response()->json(["token" => $token]);
    }

    private function validateRequestAuth(Request $request)
    {
        $this->validate($request, [
            'secret' => 'required',
            'key' => 'required'
        ]);
    }
}
