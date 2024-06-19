<?php

namespace Tests\Feature;

use Tests\ReviewTest;

class PatchReviewTest extends ReviewTest
{
    private static int $fakeReviewID = 88;

    public function test_put_review_return_error_404_with_unknown_id()
    {
        $this->put(
            self::$baseEndpoint.'/'.self::$fakeReviewID,
            [
                'work_id' => self::$realWorkID,
                'review' => 'Some Review',
                'score' => 3,
            ],
            [
                'Accept' => 'application/json',
            ]
        )->assertStatus(404);

    }

    public function test_put_review_return_error_with_parameter_missing(): void
    {
        //All parameter Missing
        $this->put(
            self::$baseEndpoint.'/'.self::$fakeReviewID,
            [],
            [
                'Accept' => 'application/json',
            ]
        )->assertStatus(422);

        //WorkID parameter Missing
        $this->put(
            self::$baseEndpoint.'/'.self::$fakeReviewID,
            [
                //"work_id" => self::$realWorkID,
                'review' => 'Some Review',
                'score' => 3,
            ],
            [
                'Accept' => 'application/json',
            ]
        )->assertStatus(422);

        //Review parameter Missing
        $this->put(
            self::$baseEndpoint.'/'.self::$fakeReviewID,
            [
                'work_id' => self::$realWorkID,
                //"review" => "Some Review",
                'score' => 3,
            ],
            [
                'Accept' => 'application/json',
            ]
        )->assertStatus(422);

        //Score parameter Missing
        $this->put(
            self::$baseEndpoint.'/'.self::$fakeReviewID,
            [
                'work_id' => self::$realWorkID,
                'review' => 'Some Review',
                //"score" => 3
            ],
            [
                'Accept' => 'application/json',
            ]
        )->assertStatus(422);
    }

    public function test_put_review_return_error_with_invalid_parameters()
    {

        //Empty Review
        $this->put(
            self::$baseEndpoint.'/'.self::$fakeReviewID,
            [
                'work_id' => self::$realWorkID,
                'review' => '',
                'score' => 3,
            ],
            [
                'Accept' => 'application/json',
            ]
        )->assertStatus(422);

        //Score Parameter over 6
        $this->put(
            self::$baseEndpoint.'/'.self::$fakeReviewID,
            [
                'work_id' => self::$realWorkID,
                'review' => 'Some Review',
                'score' => 7,
            ],
            [
                'Accept' => 'application/json',
            ]
        )->assertStatus(422);
        //Score Parameter under 1
        $this->put(
            self::$baseEndpoint.'/'.self::$fakeReviewID,
            [
                'work_id' => self::$realWorkID,
                'review' => 'Some Review',
                'score' => 0,
            ],
            [
                'Accept' => 'application/json',
            ]
        )->assertStatus(422);

    }
}
