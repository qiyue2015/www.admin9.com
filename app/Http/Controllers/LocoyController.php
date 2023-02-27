<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


        $category = Category::where('alias', $data['Category'])->firstOrFail();

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

        return $this->success('发布成功');
    }
}
