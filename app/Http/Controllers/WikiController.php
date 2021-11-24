<?php

namespace App\Http\Controllers;

class WikiController extends Controller
{
    public function index()
    {
        abort(404, "Página em desenvolvimento");
        return view('admin.wiki');
    }
}
