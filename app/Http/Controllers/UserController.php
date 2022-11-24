<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * @param  Request  $request
     * @return UserCollection
     */
    public function index(Request $request): UserCollection
    {
        $users = $request->user()->orderBy('id', 'DESC')->paginate();
        return new UserCollection($users);
    }

    /**
     * @param  UserRequest  $request
     * @return UserResource
     */
    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return new UserResource($user);
    }

    /**
     * @param  User  $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->success($id);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|min:6|max:16',
            'password' => 'required|min:6|max:16|confirmed',
        ], [
            'old_password.required' => '旧密码不能为空',
            'old_password.min' => '旧密码最少6个字符',
            'old_password.max' => '旧密码最多16个字符',
        ]);

        $request->user()->update(['password' => bcrypt($request->password)]);
        return $this->success();
    }

    /**
     * 删除指定用户
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if ($request->user()->id === $id) {
            return $this->fail('自己不能删除自己.');
        }

        if (!User::whereId($id)->exists()) {
            return $this->fail('用户不存在.');
        }

        if (User::findOrFail($id)->delete()) {
            return $this->success();
        }

        return $this->fail('删除失败.');
    }
}
