<?php

namespace App\Http\Controllers;

use App\Classes\CurlRequest;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Jobs\ProcessReview;
use App\Models\Review;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function postReview(ReviewRequest $reviewRequest): JsonResponse
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
        if (! empty($result)) {
            return response()->json(
                [
                    'error' => 'work_id already reviewed',
                ],
                400
            );
        }

        // Insert in the review table
        $review = Review::create([
            'openlibrary_work_id' => $values['work_id'],
            'review' => $values['review'],
            'score' => $values['score'],
            'review_status' => 'IN QUEUE',
        ]);

        //After the insert get the new created id and dispatch to the Job queue for later work
        $reviewID = Review::where('openlibrary_work_id', $values['work_id'])->first();
        $reviewID = $reviewID->id;

        $newJob = new ProcessReview($reviewID);
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

    public function getReview(int $reviewID): JsonResponse|ReviewResource
    {

        $resultReview = Review::where('id', $reviewID)->first();
        if (empty($resultReview)) {
            return response()->json(
                [
                    'Unknown reviewID',
                ],
                404
            );
        }

        if ($resultReview->review_status != 'DONE') {
            return response()->json(
                [],
                202
            );
        }

        $review = Review::where('id', $reviewID)->first();

        return new ReviewResource($review);
    }

    public function putReview(int $reviewID, ReviewRequest $reviewRequest): JsonResponse
    {

        $review = Review::where('id', $reviewID)->first();
        if (empty($review)) {
            return response()->json(
                [
                    'Unknown reviewID',
                ],
                404
            );
        }

        $validatedValues = $reviewRequest->validated();

        $updated = $review->update([
            'review' => $validatedValues['review'],
            'score' => $validatedValues['score'],
            'openlibrary_work_id' => $validatedValues['work_id'],
        ]);

        if (! $updated) {
            return response()->json(
                [
                    'Error in the Review Update',
                ],
                500
            );
        }

        return response()->json(
            [
                'Review Updated',
            ],
            200
        );

    }

    public function deleteReview(int $reviewID): JsonResponse
    {

        $review = Review::where('id', $reviewID)->first();
        if (empty($review)) {
            return response()->json(status: 404);
        }

        //First of all i need to delete the work
        $review->work->delete();

        // Then i can delete the review
        if ($review->delete()) {
            return response()->json(status: 200);
        }

        return response()->json(
            [
                'Error in the review delete',
            ],
            500
        );

    }
}
