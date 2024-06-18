<?php

namespace App\Classes;

class OpenLibraryClass
{
    public static string $coverLink = 'https://covers.openlibrary.org/b/id/';
    public static string $authorLink = "https://covers.openlibrary.org/a/olid/";

    public static string $imageExtension = '.jpg';
    public static array $imageSize = [
        'small' => 'S',
        'medium' => 'M',
        'large' => 'L',
    ];


    public function getWorkInfo(string $openLibraryWorkID)
    {
        $url = env( 'OPENLIBRARY_WORK_INFO_ENDPOINT' ) . $openLibraryWorkID.'.json';
        $request = CurlRequest::Get($url, []);

        return $request['body'];
    }

    public function getAuthorInfo( string $openLibraryAuthorID )
    {
        $url = env( 'OPENLIBRARY_AUTHOR_INFO_ENDPOINT' ) . $openLibraryAuthorID.'.json';
        $request = CurlRequest::Get($url, []);

        return $request['body'];
    }

}
