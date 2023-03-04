<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArchiveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $category = null;
        if ($this->category) {
            $category = [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ];
        }

        $user = null;
        if ($this->user) {
            $user = [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ];
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'has_cover' => $this->has_cover,
            'cover' => $this->cover,
            'checked' => $this->checked,
            'is_publish' => $this->is_publish,
            'publish_at' => $this->publish_at,
            'category' => $category,
            'user' => $user,
        ];
    }
}
