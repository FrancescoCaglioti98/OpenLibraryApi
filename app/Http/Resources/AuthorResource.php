<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $works = [];
        foreach ($this->works as $work) {
            $works[] = $work->getGeneralInfo();
        }

        return [
            'id' => $this->id,
            'openlibrary_id' => $this->openlibrary_author_id,
            'name' => $this->name,
            'bio' => $this->bio,
            'birth_date' => $this->birth_date ? date('Y-m-d', strtotime($this->birth_date)) : '',
            'death_date' => $this->death_date ? date('Y-m-d', strtotime($this->death_date)) : '',
            'alternative_names' => $this->alternative_names,
            'photos' => $this->photos,
            'useful_links' => $this->useful_links,
            'works' => $works,
        ];
    }
}
