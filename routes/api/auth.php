<?php

use App\Http\Controllers\AuthController;
use Illuminate\Routing\Router;

Route::controller(AuthController::class)->prefix('auth')->group(function (Router $router): void {
    $router->post('login', 'login')->name('login');
    $router->post('logout', 'logout')->name('logout')->middleware('auth:sanctum');
    $router->get('info', 'info')->name('info')->middleware('auth:sanctum');
});
