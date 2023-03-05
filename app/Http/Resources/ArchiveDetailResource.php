<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArchiveDetailResource extends JsonResource
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

        $content = '';
        if ($this->extend) {
            $content = $this->extend->content;
        }

        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category' => $category,
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $this->tags,
            'has_cover' => $this->has_cover,
            'cover' => $this->cover,
            'checked' => $this->checked,
            'published' => $this->published,
            'publish_at' => now()->parse($this->publish_at)->format('Y-m-d H:i:s'),
            'created_at' => now()->parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => now()->parse($this->updated_at)->format('Y-m-d H:i:s'),
            'user' => $user,
            'content' => $content,
        ];
    }
}
