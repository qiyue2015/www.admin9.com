<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CommonController extends Controller
{

    public function index()
    {
        $category_id = 0;
        return view('welcome', compact('category_id'));
    }

    public function search(Request $request)
    {
        $title = $request->get('keywrod');
        $list = [];
        $category_id = 0;
        return view('archive.search', compact('category_id', 'title', 'list'));
    }

    public function cover($date, $id)
    {
        $archive = Archive::find($id, ['id', 'title']);

        $dir = 'public/file/'.$date;
        Storage::makeDirectory($dir);
        $path = Storage::path($dir);
        $filename = $path.'/'.$id.'-1.jpg';

        $text = str($archive->title)->limit(20, '');

        $filepath = storage_path('app/fonts/zcool-yangyu-W04.ttf');

        $covers = Storage::files('covers');
        $coverKey = array_rand($covers);
        $cover = storage_path('app/'.$covers[$coverKey]);

        $img = Image::make($cover)
            //->text($text, 400, 160, function ($font) use ($filepath) {
            //    $font->file($filepath);
            //    $font->color('#FFFFFF');
            //    $font->size(48);
            //    $font->align('center');
            //    $font->valign('center');
            //})
            ->text($text, 400, 160, function ($font) use ($filepath) {
                $font->file($filepath);
                $font->size(48);
                $font->align('center');
                $font->valign('center');
            })
            ->save($filename);

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
