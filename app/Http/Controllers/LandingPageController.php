<?php

namespace App\Http\Controllers;

class LandingPageController extends Controller
{
    public function editor($code)
    {
        // dd($code);
        return view('admin.landing-pages.editor_frame');
    }
}
