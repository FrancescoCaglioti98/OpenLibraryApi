<?php

namespace App\Classes;

use WpOrg\Requests\Requests;

class CurlRequest
{
    public static function Get(string $url, array $searchParams = [])
    {

        if (! empty($searchParams)) {
            $url = $url.'?'.http_build_query($searchParams);
        }

        $request = Requests::get($url, [], []);

        return [
            'httpResponseCode' => $request->status_code,
            'body' => json_decode($request->body),
        ];
    }
}
