<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'nullable|exists:App\Models\Category,id',
            'parent_id' => 'numeric',
            'name' => 'required|between:2,6',
            'is_last' => 'numeric|in:0,1',
            'is_list' => 'numeric|in:0,1',
        ];
    }
}
