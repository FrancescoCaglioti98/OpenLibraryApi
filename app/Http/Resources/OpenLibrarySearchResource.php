<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpenLibrarySearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "work_id" => str_replace( "/works/", "" , $this->key ),
            "title" => $this->title,
            "authors" => $this->author_name ?? [],
        ];
    }
}
