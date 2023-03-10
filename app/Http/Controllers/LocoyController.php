<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocoyController extends Controller
{
    public function index(Request $request)
    {
        $list = Archive::select(['id', 'title', 'description'])
            ->where('checked', false)
            ->orderBy('id', 'DESC')
            ->simplePaginate(20);
        $category_id = 0;
        return view('locoy.locoy', compact('category_id', 'list'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        \Log::debug('火车头发布', $request->get('locoy'));

        $data = $request->validate([
            'CateID' => 'required|string',
            'Title' => 'required|string',
            'Content' => 'required|string',
            'TpTaskId' => 'required|string',
        ]);

        $categories = [
            13 => 11, // 金融
            12 => 4, // 娱乐
            11 => 5, // 教育
            10 => 1, // 问答
            9 => 10, // 汽车
            8 => 8, // 游戏
            7 => 1, // 图文
            6 => 5, // 知识
            5 => 19, // 人物
            4 => 7, // 美食
            3 => 15, // 旅游
            2 => 7, // 生活
        ];
        $CateID = (int) $data['CateID'];
        $categoryId = $categories[$CateID];

        DB::transaction(function () use ($categoryId, $data) {
            $user_id = random_int(10, 100);
            $archive = Archive::create([
                'category_id' => $categoryId,
                'user_id' => $user_id,
                'title' => $data['Title'],
                'search_title' => $data['Title'],
                'task_id' => $data['TpTaskId'],
            ]);
            $archive->extend()->create([
                'content' => $data['Content'],
            ]);
        });

        return $this->success();
    }
}
