<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'last_edited' => $this->updated_at,
            'content' => $this->content,
        ];
    }
}
