<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Test for the `/api/search` endpoint
 */
class SearchTest extends TestCase
{
    private static string $baseEndpoint = "/api/search";

    /**
     *  I need to verify that i get an error 422 if no query parameters are given
     * @return void
     */
    public function test_search_book_return_error_when_no_query_given(): void
    {
        $response = $this->get(
            self::$baseEndpoint,
            [
                'Accept' => 'application/json',
            ]
        );
        $response->assertStatus(422);
    }

    /**
     * I need to vverify thtat i get an error 422 when i give a wrong identifier for the query string
     * @return void
     */
    public function test_search_book_return_error_when_wrong_query_identifier_given(): void
    {
        $response = $this->get(
            self::$baseEndpoint . "?wrongIdentifier=test",
            [
                'Accept' => 'application/json',
            ]
        );
        $response->assertStatus(422);
    }

    /**
     * Test that when i give the right but empty query string identifier i will recive a 422 error
     * @return void
     */
    public function test_search_books_return_error_when_right_empty_query_identifier_given(): void
    {
        $response = $this->get(
            self::$baseEndpoint . "?searchTerm",
            [
                'Accept' => 'application/json',
            ]
        );

        $response->assertStatus(422);
    }

    public function test_search_books_return_data_when_right_identifier_given(): void
    {

        $response = $this->get(
            self::$baseEndpoint . "?searchTerm=Tolkien",
            [
                'Accept' => 'application/json',
            ]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    "work_id",
                    "title",
                    "authors"
                ]
            ]
        ]);

    }

}
