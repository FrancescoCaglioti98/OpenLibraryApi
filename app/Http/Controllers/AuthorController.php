<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;


class AuthorController extends Controller
{

    public function getAuthor( int $authorID )
    {

        $author = Author::where( "id", $authorID )->first();
        if( empty( $author ) ) {
            return response()->json(
                [
                    "Unknown authorID"
                ],
                404
            );
        }

        return new AuthorResource( $author );

    }

}
