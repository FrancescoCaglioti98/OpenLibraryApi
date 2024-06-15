<?php

namespace App\Http\Controllers;

use App\Classes\CurlRequest;
use App\Http\Requests\OpenLibrarySearchRequest;
use App\Http\Resources\OpenLibrarySearchResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OpenLibrarySearch extends Controller
{
    public function Search(OpenLibrarySearchRequest $request): AnonymousResourceCollection
    {

        $searchTerm = [
            'q' => $request->input('searchTerm'),
            "fields" => 'key,title,author_name'
        ];

        $response = CurlRequest::Get($_ENV['OPENLIBRARY_SEARCH_ENDPOINT'], $searchTerm);

        $bodyResponse = $response["body"];
        return OpenLibrarySearchResource::collection( $bodyResponse->docs );
    }
}
