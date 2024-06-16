<?php

namespace App\Jobs;

use App\Classes\OpenLibraryClass;
use App\Http\Resources\WorkResource;
use App\Models\Review;
use App\Models\Work;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessReview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private OpenLibraryClass $openLibraryClass;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly int $reviewID
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle()
    {

        $this->openLibraryClass = new OpenLibraryClass();

        $reviewInfo = Review::where( "id", $this->reviewID )->first();
        Review::where( "id", $this->reviewID )->update( [ "review_status" => "WORKING" ] );


        //First Step: Get all the info from the WORK id
        $workInfo = $this->openLibraryClass->getWorkInfo( $reviewInfo->openlibrary_work_id );

        //Second step: Create a record for the new Work
        $work = Work::create([
            'openlibrary_work_id' => str_replace('/works/', '', $workInfo->key),
            'title' => $workInfo->title,
            'description' => $workInfo->description,
            'first_publish' => date( 'Y-m-d', strtotime( $workInfo->first_publish_date ) ) ?? null
        ]);

        //Second Step: Save all the links
        foreach ( $workInfo->links as $link ) {
            DB::table( "work_links" )->insert([
                "work_id" => $work->id,
                "title" => $link->title,
                "link" => $link->url
            ]);
        }

        //Third Step: Save all the covers
        foreach ( $workInfo->covers as $cover ) {
            DB::table( "work_covers" )->insert([
                "work_id" => $work->id,
                "cover" => $cover
            ]);
        }

        //Fourth step: Save all the optional subject People
        $subjectPeoples = $workInfo->subject_people ?? [];
        foreach ( $subjectPeoples as $subjectPeople ) {
            DB::table( "work_subject_peoples" )->insert([
                "work_id" => $work->id,
                "people" => $subjectPeople
            ]);
        }

        //Fifth Step: Save all the subject
        $subjects = $workInfo->subjects ?? [];
        foreach ( $subjects as $subject ) {
            DB::table( "work_subjects" )->insert([
                "work_id" => $work->id,
                "subject" => $subject
            ]);
        }

        //SESTO(?) Step: Save all the optional subject times
        $subjectTimes = $workInfo->subject_times ?? [];
        foreach ( $subjectTimes as $subjectTime ) {
            DB::table( "work_subject_times" )->insert([
                "work_id" => $work->id,
                "time" => $subjectTime
            ]);
        }

        //SETTIMO(?) Step: Save all the optional subject places
        $subjectPlaces = $workInfo->subject_places ?? [];
        foreach ( $subjectPlaces as $subjectPlace ) {
            DB::table( "work_subject_places" )->insert([
                "work_id" => $work->id,
                "place" => $subjectPlace
            ]);
        }


        //$workReturn = Work::where( "id", $work->id )->get();
        //return WorkResource::collection( $workReturn );

        //Then is time for the authors section
        $authors = $workInfo->authors ?? [];
        foreach ( $authors as $author ) {



        }



    }
}
