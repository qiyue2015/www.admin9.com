<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Overtrue\Pinyin\Pinyin;

class LocoyController extends Controller
{
    public function index(Request $request)
    {
        $list = Archive::where('checked', false)
            ->orderBy('id', 'DESC')
            ->limit(100)
            ->get();
        return view('locoy.locoy', compact('list'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        Log::channel('locoyPost')->info('火车头发布', $request->post());
        $data = $request->validate([
            'locoy' => 'required|string',
            'Category' => 'required|string',
            'Title' => 'required|string',
            'Content' => 'required|string',
            'task_id' => 'required|string',
        ]);

        if ($data['locoy'] !== 'abcdefg') {
            return $this->fail('密钥不正确');
        }


        $category = Category::where('alias', $data['Category'])->first();
        if (is_null($category)) {
            $category = Category::create([
                'name' => $data['Category'],
                'alias' => $data['Category'],
                'slug' => Pinyin::permalink($data['Category'], ''),
            ]);
        }

        DB::transaction(function () use ($category, $data) {
            $user_id = random_int(10, 100);
            $archive = Archive::create([
                'category_id' => $category->id,
                'user_id' => $user_id,
                'title' => $data['Title'],
                'search_title' => $data['Title'],
                'task_id' => $data['task_id'],
            ]);
            $archive->extend()->create([
                'content' => $data['Content'],
            ]);
        });

        return $this->success();
    }
}
