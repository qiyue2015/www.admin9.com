<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginResource;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * @param  LoginRequest  $request
     * @return LoginResource
     * @throws ValidationException
     */
    public function login(LoginRequest $request): LoginResource
    {
        $request->authenticateOrFail();

        /** @var User $user */
        $user = $request->user();
        $permissions = $user->getAllPermissions()->pluck('name');
        /** @var string $tokenName */
        $tokenName = $request->input('token_name', 'web');
        $token = $user->createExpirableToken($tokenName, $permissions->toArray());

        /** Delete the existing token to achieve single sign on */
        PersonalAccessToken::query()->where('tokenable_id', $token->accessToken->tokenable_id)
            ->where('id', '<', $token->accessToken->id)
            ->where('tokenable_type', $token->accessToken->tokenable_type)
            ->delete();

        return new LoginResource($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();
        $token->delete();

        return $this->success();
    }

    public function info(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function permissions(Request $request)
    {
        $permissions = $request->user()->getAllPermissions();
        return PermissionResource::collection($permissions);
    }
}
