<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    public function generateTitleImage(Request $request): JsonResponse
    {
        /**
         * 验证请求参数
         */
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:30',
        ]);

        if ($validator->fails()) {
            return $this->fail($validator->errors()->first());
        }

        $title = $request->input('title');
        return $this->success([
            'image_url' => makeTitleCover($title),
        ]);
    }
}

