<?php

namespace App\Http\Controllers;

use App\Classes\CurlRequest;
use App\Http\Requests\ReviewRequest;
use App\Jobs\ProcessReview;
use App\Models\Review;
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
        $result = Review::where('work_id', $values['work_id'])->first();
        if (! empty($result)) {
            return response()->json(
                [
                    'error' => 'work_id already reviewed',
                ],
                400
            );
        }

        // If the workID exists, i will dispatch the new "Job" to the queue for later work
        // And then respond with a status code of 201
        $newJob = new ProcessReview(
            $values['work_id'],
            $values['review'],
            $values['score']
        );
        dispatch($newJob);

        //        //After the dispatch i will need to get from the queue table the ID for the new job addedd
        //        $result = DB::table("jobs")
        //            ->where( "payload", "like", "%" . $values["work_id"] . "%" )
        //            ->orderBy( "id", "desc" )
        //            ->pluck("id")
        //            ->first();

        return response()->json(
            [
                'message' => 'Job Created',
                'work_id' => $values['work_id'],
            ]
        );

    }

    private function checkIfValidWorkID(string $workID): bool
    {

        $url = $_ENV['OPENLIBRARY_GET_INFO_MAIN_ENDPOINT'].$workID.'.json';

        $request = CurlRequest::Get($url, []);

        if ($request['httpResponseCode'] != 200) {
            return false;
        }

        return true;

    }
}
