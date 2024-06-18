<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkResource;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{

    public function getWork( int $workID )
    {

        $work = Work::where( "id", $workID )->first();
        if( empty( $work ) ) {
            return response()->json(
                [
                    "Unknown workID"
                ],
                404
            );
        }

        return new WorkResource( $work );
    }

}
