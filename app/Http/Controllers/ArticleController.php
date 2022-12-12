<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Optimus\Optimus;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param $id
     * @param  Optimus  $optimus
     * @return Application|Factory|View
     */
    public function show($id, Optimus $optimus): View|Factory|Application
    {
        $id = $optimus->decode($id);
        $article = cache()->rememberForever('view:'.$id, function () use ($id) {
            return Article::find($id);
        });
        if (is_null($article)) {
            abort(404);
        }

        $tablename = 'articles_'.$article->id % 10;
        $data = DB::table($tablename)->find($article->id);
        $content = $data->content;

        $prev = cache()->remember('prev:'.$article->id, now()->endOfDay(), function () use ($article) {
            return Article::where('id', '<', $article->id)
                //->where('category_id', $article->category_id)
                ->checked()
                ->first();
        });

        $next = cache()->remember('next:'.$article->id, now()->endOfDay(), function () use ($article) {
            return Article::where('id', '>', $article->id)
                //->where('category_id', $article->category_id)
                ->checked()
                ->first();
        });

        $hotList = cache()->remember('hot:'.$article->category_id, now()->endOfDay(), function () use ($article) {
            return Article::whereCategoryId($article->category_id)
                ->where('id', '<', $article->id)
                ->where('checked', true)
                ->orderByDesc('id')
                ->take(10)
                ->get();
        });

        return view('article.show', compact(
            'article', 'content',
            'next', 'prev',
            'hotList'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
