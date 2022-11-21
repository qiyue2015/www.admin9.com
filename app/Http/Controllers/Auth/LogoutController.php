<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->tokens()->delete();

        return $this->success();
    }
}
