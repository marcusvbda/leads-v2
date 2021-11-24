<?php

namespace App\Http\Controllers;

class WikiController extends Controller
{
    public function index()
    {
        return view('admin.wiki');
    }
}
