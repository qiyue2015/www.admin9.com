<?php

use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\UserController;
use Illuminate\Routing\Router;

Route::controller(UserController::class)->middleware(['auth:sanctum'])->group(function (Router $router) {
    $router->get('users', 'index')->middleware('abilities:users.index');
    $router->post('users', 'store')->middleware('abilities:users.store');
    $router->get('users/{user}', 'show')->middleware('abilities:users.show');
    $router->put('users/{user}', 'update')->middleware('abilities:users.update');
    $router->delete('users/{user}', 'destroy')->middleware('abilities:users.destroy');
    $router->get('users/{id}', 'show')->middleware('abilities:users.show');
});
