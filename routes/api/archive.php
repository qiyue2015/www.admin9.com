<?php

use App\Http\Controllers\Api\ArchiveController;
use Illuminate\Routing\Router;

Route::apiResource('archives', ArchiveController::class)->middleware(['auth:sanctum']);
Route::controller(ArchiveController::class)
    ->middleware(['auth:sanctum'])
    ->group(function (Router $router) {
        $router->post('archives/{archive}/checked', 'checked');
        $router->post('archives/{archive}/publish', 'publish');
    });
