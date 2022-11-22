<?php

namespace App\Http\Resources;

class LoginResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'token_type' => 'Bearer',
            'plain_text' => $this->plainTextToken,
        ];
    }
}
