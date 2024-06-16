<?php

namespace App\Http\Controllers;

use App\Classes\CurlRequest;
use App\Http\Requests\ReviewRequest;
use App\Jobs\ProcessReview;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function postReview(ReviewRequest $reviewRequest)
    {

        $values = $reviewRequest->validated();

        //First of all i need to check that the WorkID is a valid identifier
        $existWorkID = $this->checkIfValidWorkID($values['work_id']);
        if (! $existWorkID) {
            return response()->json(
                [
                    'error' => 'Wrong work_id given',
                ],
                400
            );
        }



        // I need to verify if the WorkID has already a review
        $result = Review::where('openlibrary_work_id', $values['work_id'])->first();

        //DEBUG
        $result = [];

        if (! empty($result) ) {
            return response()->json(
                [
                    'error' => 'work_id already reviewed',
                ],
                400
            );
        }

        // Insert in the review table
        //DEBUG
        $review = Review::create([
            'openlibrary_work_id' => $values['work_id'],
            'review' => $values['review'],
            'score' => $values['score'],
            'review_status' => "IN QUEUE",
        ]);

        //After the insert get the new created id and dispatch to the Job queue for later work
        $reviewID = Review::where( "openlibrary_work_id", $values["work_id"] )->first();
        $reviewID = $reviewID->id;

        $newJob = new ProcessReview( $reviewID );

        //JustFor test porpuse
        return $newJob->handle();


        dispatch($newJob);

        return response()->json(
            [
                'message' => 'Job Created',
                'review_identifier' => $reviewID,
            ],
            201
        );

    }

    private function checkIfValidWorkID(string $workID): bool
    {

        $url = $_ENV['OPENLIBRARY_WORK_INFO_ENDPOINT'].$workID.'.json';

        $request = CurlRequest::Get($url, []);

        if ($request['httpResponseCode'] != 200) {
            return false;
        }

        return true;

    }
}
