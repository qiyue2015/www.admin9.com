<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Category;

class ArchiveController extends Controller
{
    public function index($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category_id = $category->id;
        return view('welcome', compact('category', 'category_id'));
    }

    public function show($id)
    {
        $archive = cache()->remember('archives:'.$id, now()->addMinutes(60), function () use ($id) {
            return Archive::find($id);
        });
        $category = $archive->category;
        $category_id = $category->id;
        return view('archive.show', compact('archive', 'category', 'category_id'));
    }
}
