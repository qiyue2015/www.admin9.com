<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->prefix('auth')->group(function (Router $router): void {
    $router->post('login', 'login')->name('login');
    $router->post('logout', 'logout')->name('logout')->middleware('auth:sanctum');
    $router->get('info', 'info')->name('info')->middleware('auth:sanctum');
    $router->get('permissions', 'permissions')->name('permissions')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function (Router $router) {
    $router->apiResource('roles', RoleController::class);
});
