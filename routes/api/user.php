<?php

use App\Http\Controllers\UserController;
use Illuminate\Routing\Router;

Route::controller(UserController::class)->middleware(['auth:sanctum'])->group(function (Router $router) {
    $router->get('users', 'index')->middleware('abilities:user.index');
    $router->post('users', 'store')->middleware('abilities:user.store');
    $router->get('users/{user}', 'show')->middleware('abilities:user.show');
    $router->put('users/changePassword', 'changePassword')->middleware('abilities:user.change-password');
    //$router->put('users/{user}', 'update')->middleware('abilities:user.update');
    $router->delete('users/{user}', 'destroy')->middleware('abilities:user.destroy');
    $router->get('users/{id}', 'show')->middleware('abilities:user.show');
});
