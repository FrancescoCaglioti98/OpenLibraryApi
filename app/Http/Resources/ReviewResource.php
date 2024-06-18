<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $workInfo = [
            'id' => $this->work->id,
            'book' => $this->work->title,
            'openlibrary_id' => $this->work->openlibrary_work_id,
            'link' => $_ENV['APP_URL'].'/api/work/'.$this->work->id,
        ];

        $authors = [];
        foreach ($this->work->authors as $author) {
            $authors[] = $author->getGeneralInfo();
        }

        return [
            'id' => $this->id,
            'work_id' => $this->openlibrary_work_id,
            'review' => $this->review,
            'score' => $this->score,
            'work_info' => $workInfo,
            'authors_info' => $authors,
        ];
    }
}
