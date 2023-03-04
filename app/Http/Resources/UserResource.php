<?php

namespace App\Http\Resources;

class UserResource extends BaseResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var \App\Models\User|static $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => ['ALL_ROUTERS'],
        ];
    }
}
