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

        // 底图目录
        $backgroundDir = resource_path('title_backgrounds');
        $backgrounds = File::files($backgroundDir);
        if (empty($backgrounds)) {
            return $this->fail('背景图片为空.');
        }

        // 随机获取一张背景图
        $background = $backgrounds[array_rand($backgrounds)];

        // 字体
        $fontSize = getFontSize($title);
        $fontPath = resource_path('fonts/zcool-yangyu-W04.ttf');

        // 根据文件名里的关键字来决定文字使用颜色
        $color = '#000000';
        if (str()->contains($background->getBasename(), 'white')) {
            $color = '#FFFFFF';
        } elseif (str()->contains($background->getBasename(), 'tea')) {
            $color = '#134e4a';
        }

        // 判断图片是否已存在
        $imageFilename = md5($title).'-'.$background->getBasename();
        $imagePath = 'title_background_temps/'.$imageFilename;
        if (Storage::disk('public')->exists($imagePath)) {
            return $this->success([
                'image_url' => Storage::url($imagePath),
            ]);
        }

        // 生成图片
        $imageTempPath = Storage::disk('public')->path($imagePath);
        Image::make($background->getPathname())
            ->text($title, 400, 160, function ($font) use ($fontSize, $fontPath, $color) {
                $font->file($fontPath);
                $font->size($fontSize);
                $font->color($color);
                $font->align('center');
                $font->valign('center');
            })
            ->save($imageTempPath);

        return $this->success([
            'image_url' => Storage::url($imagePath),
        ]);
    }

}

