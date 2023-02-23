<?php

namespace App\Jobs;

use App\Models\Archive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GenerateTitleCoverJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Archive $archive;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Archive $archive)
    {
        $this->archive = $archive;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $directory = 'public/files/'.now()->parse($this->archive->created_at)->format('Ymd');
        $imageFilename = $directory.'/'.$this->archive->id.'-1.jpg';

        // 创建目录
        Storage::makeDirectory($directory);

        // 字体
        $fontSize = getFontSize($this->archive->title);
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
        Image::make($randomCoverFile->getPathname())
            ->text($this->archive->title, 400, 160, function ($font) use ($fontSize, $fontPath, $color) {
                $font->file($fontPath);
                $font->size($fontSize);
                $font->color($color);
                $font->align('center');
                $font->valign('center');
            })
            ->save($imageFilePath);

        $this->archive->update([
            'cover' => $imageFilename,
            'has_cover' => true,
        ]);
    }
}
