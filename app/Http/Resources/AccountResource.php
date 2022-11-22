<?php

namespace App\Http\Resources;

class AccountResource extends BaseResource
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
            'id' => $this->id,
            'name' => $this->name,
            'account' => $this->account,
            'original' => $this->original,
            'signature' => $this->signature,
            'biz' => $this->biz,
            'avatar' => $this->avatar,
        ];
    }
}
