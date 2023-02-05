<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\LocoyController;
use App\Models\Article;
use App\Models\User;
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

Route::get('/', static function () {
    $news = cache()->remember('home:recommend', now()->addMinutes(5), function () {
        return Article::checked()
            ->orderByDesc('id')
            ->take(90)
            ->get();
    });
    return view('welcome', compact('news'));
});

Route::get('/locoy', [LocoyController::class, 'index']);
Route::post('/locoy', [LocoyController::class, 'store']);

Route::get('7527/abcdefg', function () {
    $user = User::find(1);
    Auth::login($user);
    return redirect()->route('horizon.index');
});

// 文章详情
Route::get('/view/{id}.html', [ArticleController::class, 'show'])
    ->where(['id' => '[0-9]+'])
    ->name('article.show');
