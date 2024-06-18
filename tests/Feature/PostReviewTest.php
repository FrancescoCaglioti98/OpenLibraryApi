<?php

namespace Tests\Feature;

use Tests\ReviewTest;

class PostReviewTest extends ReviewTest
{

    public function test_post_review_return_error_with_parameter_missing(): void
    {
        //All parameter Missing
        $this->postJson(
            self::$baseEndpoint,
            [],
            [
                'Accept' => 'application/json',
            ]
        )->assertStatus(422);

        //WorkID parameter Missing
        $this->postJson(
            self::$baseEndpoint,
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
        $this->postJson(
            self::$baseEndpoint,
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
        $this->postJson(
            self::$baseEndpoint,
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

    public function test_post_review_return_error_with_invalid_parameters()
    {
        //Fake WorkID
        $this->postJson(
            self::$baseEndpoint,
            [
                'work_id' => self::$fakeWorkID,
                'review' => 'Some Review',
                'score' => 3,
            ],
            [
                'Accept' => 'application/json',
            ]
        )->assertStatus(400);

        //Empty Review
        $this->postJson(
            self::$baseEndpoint,
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
        $this->postJson(
            self::$baseEndpoint,
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
        $this->postJson(
            self::$baseEndpoint,
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

    public function test_post_review_return_review_id()
    {

        $response = $this->postJson(
            self::$baseEndpoint,
            [
                'work_id' => self::$realWorkID,
                'review' => 'Some Review',
                'score' => 3,
            ],
            [
                'Accept' => 'application/json',
            ]
        );

        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                'message',
                'review_identifier',
            ]
        );
    }

    public function test_post_review_return_error_if_multiple_times_same_work_id()
    {

        //First right post
        $this->postJson(
            self::$baseEndpoint,
            [
                'work_id' => self::$realWorkID,
                'review' => 'Some Review',
                'score' => 3,
            ],
            [
                'Accept' => 'application/json',
            ]
        )->assertStatus(201);

        //Second post in error
        $this->postJson(
            self::$baseEndpoint,
            [
                'work_id' => self::$realWorkID,
                'review' => 'Some Review',
                'score' => 3,
            ],
            [
                'Accept' => 'application/json',
            ]
        )->assertStatus(400);

    }
}
