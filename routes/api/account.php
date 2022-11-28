<?php

use App\Http\Controllers\AccountController;
use Illuminate\Routing\Router;

Route::controller(AccountController::class)->middleware(['auth:sanctum'])->group(function (Router $router) {
    $router->get('accounts', 'index')->middleware('abilities:accounts.index');
    $router->post('accounts', 'store')->middleware('abilities:accounts.store');
    $router->get('accounts/{account}', 'show')->middleware('abilities:accounts.show');
    $router->put('accounts/{account}', 'update')->middleware('abilities:accounts.update');
    $router->delete('accounts/{account}', 'destroy')->middleware('abilities:accounts.destroy');
    $router->get('accounts/{id}', 'show')->middleware('abilities:accounts.show');
});
