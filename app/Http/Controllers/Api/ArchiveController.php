<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArchiveCollection;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Archive;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(request $request): \Illuminate\Http\JsonResponse
    {
        $list = Archive::with(['category', 'user'])
            ->when($request->get('is_publish'), function ($query) use ($request) {
                $query->where('is_publish', $request->get('is_publish'));
            })
            ->when($request->get('checked'), function ($query) use ($request) {
                $query->where('checked', $request->get('checked'));
            })
            ->orderBy('id', 'DESC')
            ->paginate(20);

        return $this->successPaginate(new ArchiveCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $archive = new Archive($data);
        $archive->user_id = $request->user()->id;
        $archive->save();
        return $this->success($archive);
    }

    /**
     * @param  Archive  $archive
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Archive $archive)
    {
        return $this->success($archive);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request  $request
     * @param  Archive  $archive
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Archive $archive)
    {
        $data = $request->all(['title']);
        $archive->update($data);
        return $this->success($archive);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  Archive  $archive
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Archive $archive)
    {
        if ($request->user()->id !== 1 && $archive->user_id !== $request->user()->id) {
            return $this->fail('不能删除他人信息');
        }

        if ($archive->extend()->delete()) {
            $archive->delete();
        }

        return $this->success();
    }

    public function checked(Archive $archive)
    {
        if ($archive->checked) {
            $archive->update(['checked' => false]);
        } else {
            $archive->update(['checked' => true]);
        }

        return $this->success();
    }

    public function publish(Archive $archive)
    {
        if (!$archive->checked) {
            return $this->fail('该文档未审核');
        }

        if ($archive->is_publish) {
            return $this->fail('该文档已发布');
        }

        $archive->update(['is_publish' => false, 'publish_at' => now()]);

        return $this->success();
    }
}
