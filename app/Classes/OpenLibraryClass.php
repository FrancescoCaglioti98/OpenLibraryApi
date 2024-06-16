<?php

namespace App\Classes;

class OpenLibraryClass
{

    public static string $coverLink = "https://covers.openlibrary.org/b/id/";
    public static string $coverExtension = ".jpg";
    public static array $coverSizes = [
        "small" => "S",
        "medium" => "M",
        "large" => "L"
    ];


    public function getWorkInfo( string $openLibraryWorkID )
    {
        $url = $_ENV['OPENLIBRARY_WORK_INFO_ENDPOINT'] . $openLibraryWorkID .'.json';
        $request = CurlRequest::Get($url, []);
        return $request["body"];
    }

}
