<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocoyController extends Controller
{
    /**
     * 待采集例表
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $categoryIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 24, 26, 27, 28, 29, 30, 31, 32];
        $limit = $request->get('limit', 100);
        $data = Article::where('checked', 0)->orderByDesc('id')->simplePaginate($limit);
        return view('locoy', compact('data', 'categoryIds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'required',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string',
            'content' => 'required|string',
            'created_at' => 'string',
        ]);
        $article = Article::find($request->get('id'));
        if (is_null($article)) {
            exit('ID 不存在.');
        }
        unset($data['id'], $data['content']);
        $data['checked'] = true;
        $article->fill($data);
        $article->save();

        $subtable = $article->id % 10;
        DB::table('articles_'.$subtable)->updateOrInsert([
            'id' => $article->id,
        ], [
            'content' => $request->get('content'),
        ]);
        exit('发布成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
