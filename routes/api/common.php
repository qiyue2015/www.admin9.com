<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

Route::post('webhook', function (Request $request) {
    \Illuminate\Support\Facades\Log::info('Chat GPT', ['content' => $request->post()]);
    return '操作成功';
});

Route::controller(AuthController::class)->prefix('auth')->group(function (Router $router): void {
    $router->post('login', 'login')->name('login');
    $router->post('logout', 'logout')->name('logout')->middleware('auth:sanctum');
    $router->get('info', 'info')->name('info')->middleware('auth:sanctum');
    $router->get('permissions', 'permissions')->name('permissions')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function (Router $router) {
    $router->apiResource('roles', RoleController::class);
});
