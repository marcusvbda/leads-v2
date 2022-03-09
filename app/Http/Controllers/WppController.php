<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WppController extends Controller
{
    public function sender()
    {
        return view('admin.wpp.sender');
    }

    public function tokenUpdate(Request $request)
    {
        $user = Auth::user();
        $user->setWppLastSession($request->token);

        return response()->json(['success' => true]);
    }
}
