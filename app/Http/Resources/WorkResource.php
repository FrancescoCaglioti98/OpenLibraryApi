<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $authors = [];
        foreach ($this->authors as $author) {
            $authors[] = $author->getGeneralInfo();
        }

        return [
            'id' => $this->id,
            'openlibrary_id' => $this->openlibrary_work_id,
            'title' => $this->title,
            'description' => $this->description,
            'first_publish' => $this->first_publish,
            'links' => $this->links,
            'covers' => $this->covers,
            'subject' => $this->subject,
            'subject_people' => $this->subject_people,
            'subject_times' => $this->subject_times,
            'subject_places' => $this->subject_places,
            "authors" => $authors,
        ];
    }
}
