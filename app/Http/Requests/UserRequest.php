<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|between:2,6',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|alpha_dash|min:6',
        ];
    }
}
