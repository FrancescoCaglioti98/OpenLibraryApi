<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorResource;
use App\Models\Author;

class AuthorController extends Controller
{
    public function getAuthor(int $authorID)
    {

        $author = Author::where('id', $authorID)->first();
        if (empty($author)) {
            return response()->json(
                [
                    'Unknown authorID',
                ],
                404
            );
        }

        return new AuthorResource($author);

    }
}
