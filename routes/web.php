<?php

use App\Http\Controllers\ArticleController;
use App\Models\Article;
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

Route::get('/', function () {
    $news = Article::limit(20)->get();
    return view('welcome', compact('news'));
});

// 文章详情
Route::get('/view/{id}.html', [ArticleController::class, 'show'])->where(['id' => '[0-9]+'])->name('article.show');
