<?php

namespace App\Http\Controllers;

use App\Classes\CurlRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    public function postReview( ReviewRequest $reviewRequest )
    {

        $values = $reviewRequest->validated();

        //First of all i need to check that the WorkID is a valid identifier
        $existWorkID = $this->checkIfValidWorkID( $values["work_id"] );
        if( !$existWorkID ) {
            return response()->json(
                [
                    "error" =>"Wrong work_id given"
                ],
                400
            );
        }



    }

    private function checkIfValidWorkID( string $workID ): bool
    {

        $url = $_ENV["OPENLIBRARY_GET_INFO_MAIN_ENDPOINT"] . $workID . ".json";

        $request = CurlRequest::Get( $url, [] );

        if( $request["httpResponseCode"] != 200 ) {
            return false;
        }
        return true;

    }

}
