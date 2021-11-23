<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function generateToken(Request $request)
    {
        $this->validateRequestAuth($request);
        return response()->json(true);
    }

    private function validateRequestAuth(Request $request)
    {
        $this->validate($request, [
            'secret' => 'required',
            'key' => 'required'
        ]);
    }
}
