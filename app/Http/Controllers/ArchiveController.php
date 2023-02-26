<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Jenssegers\Optimus\Optimus;

class ArchiveController extends Controller
{
    public function index($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category_id = $category->id;
        return view('archive.category', compact('category', 'category_id'));
    }

    /**
     * @param $id
     * @param  Optimus  $optimus
     * @return Application|Factory|View
     */
    public function show($id, Optimus $optimus): View|Factory|Application
    {
        $id = $optimus->decode($id);
        $archive = cache()->remember('archives:'.$id, now()->addMinutes(60), function () use ($id) {
            return Archive::find($id);
        });
        $category = $archive->category;
        $category_id = $category->id;
        return view('archive.show', compact('archive', 'category', 'category_id'));
    }
}
