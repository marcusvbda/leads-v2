<?php

namespace App\Http\Controllers;

use App\Http\Models\WikiPage;

class WikiController extends Controller
{
    public function show($path)
    {
        $page = WikiPage::where('path', $path)->firstOrFail();
        return view('admin.wiki.post', compact('page'));
    }

    public function wikiIframe($path)
    {
        $page = WikiPage::where('path', $path)->firstOrFail();
        return $page->body;
    }
}
