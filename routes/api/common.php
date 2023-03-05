<?php

use App\Http\Controllers\Api\CommonController;
use Illuminate\Http\Request;

Route::post('webhook', function (Request $request) {
    \Illuminate\Support\Facades\Log::info('Chat GPT', ['content' => $request->post()]);
    return '操作成功';
});

Route::controller(CommonController::class)->middleware(['auth:sanctum'])->prefix('common')->group(function ($router) {
    $router->post('generate-title-image', [CommonController::class, 'generateTitleImage']);
});

