<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CommonController extends Controller
{

    public function index(): Factory|View|Application
    {
        $category_id = 0;
        return view('welcome', compact('category_id'));
    }

    public function search(Request $request): Factory|View|Application
    {
        $title = $request->get('keywrod');
        $list = [];
        $category_id = 0;
        return view('archive.search', compact('category_id', 'title', 'list'));
    }

    public function cover($date, $id)
    {
        $archive = Archive::find($id, ['id', 'title']);


        $directory = 'public/files/'.now()->parse($archive->updated_at)->format('Ymd');
        $imageFilename = $directory.'/'.$archive->id.'-1.jpg';

        // 创建目录
        Storage::makeDirectory($directory);

        // 字体
        $fontSize = getFontSize($archive->title);
        $fontPath = resource_path('fonts/zcool-yangyu-W04.ttf');

        // 目录下所有封面图片
        $coverFiles = File::files(resource_path('cover'), ['png', 'jpg', 'jpeg']);

        // 随机取得1个
        $randomCoverIndex = array_rand($coverFiles);
        $randomCoverFile = $coverFiles[$randomCoverIndex];

        // 根据文件名里的关键字来决定文字使用颜色
        $color = '#000000';
        if (str()->contains($randomCoverFile->getBasename(), 'white')) {
            $color = '#FFFFFF';
        } elseif (str()->contains($randomCoverFile->getBasename(), 'tea')) {
            $color = '#134e4a';
        }

        // 最终生成图片的地址
        $imageFilePath = Storage::path($imageFilename);
        $img = Image::make($randomCoverFile->getPathname())
            ->text($archive->title, 400, 160, function ($font) use ($fontSize, $fontPath, $color) {
                $font->file($fontPath);
                $font->size($fontSize);
                $font->color($color);
                $font->align('center');
                $font->valign('center');
            })
            ->save($imageFilePath);

        ob_end_clean();

        return $img->response('jpg');
    }

    public function download($date, $filename)
    {
        $url = "https://www.yebaike.com/d/file/{$date}/{$filename}";
        try {
            $response = Http::get($url);
            $dir = 'file/'.$date.'/';
            Storage::disk('public')->makeDirectory($dir);
            $filePath = $dir.$filename;
            $path = Storage::disk('public')->path($filePath);
            return Image::make($response->body())->save($path)->response('jpg');
        } catch (\Exception $exception) {
            //dd($exception->getMessage());
        }
    }
}
