<?php

use App\Http\Controllers\RoleController;

Route::apiResource('role', RoleController::class)->middleware('auth:sanctum');
