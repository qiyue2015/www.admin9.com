<?php

use Illuminate\Http\Request;

Route::post('webhook', function (Request $request) {
    \Illuminate\Support\Facades\Log::info('Chat GPT', ['content' => $request->post()]);
    return '操作成功';
});
