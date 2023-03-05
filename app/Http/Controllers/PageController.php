<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $category_id = 0;
        return view('welcome', compact('category_id'));
    }

    public function search(Request $request)
    {
        $title = $request->get('keywrod');
        $list = [];
        $category_id = 0;
        return view('archive.search', compact('category_id', 'title', 'list'));
    }
}
