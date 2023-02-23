<?php

use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// 生成封面图片
Route::get('storage/file/{date}/{id}-1.jpg', [CommonController::class, 'cover']);

// 下载封面图片
Route::get('storage/file/{date}/{filename}', [CommonController::class, 'download']);
