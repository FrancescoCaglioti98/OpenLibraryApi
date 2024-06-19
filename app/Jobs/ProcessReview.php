<?php

namespace App\Jobs;

use App\Classes\OpenLibraryClass;
use App\Models\Author;
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
    public function handle(): void
    {

        $openLibraryClass = new OpenLibraryClass();

        $reviewInfo = Review::where('id', $this->reviewID)->first();
        Review::where('id', $this->reviewID)->update(['review_status' => 'WORKING']);

        //First Step: Get all the info from the WORK id
        $workInfo = $openLibraryClass->getWorkInfo($reviewInfo->openlibrary_work_id);

        //Second step: Create a record for the new Work
        $work = Work::create([
            'openlibrary_work_id' => str_replace('/works/', '', $workInfo->key),
            'title' => $workInfo->title,
            'description' => $workInfo->description,
            'first_publish' => date('Y-m-d', strtotime($workInfo->first_publish_date)) ?? null,
        ]);

        //Second Step: Save all the links
        $workLinks = $workInfo->links ?? [];
        foreach ($workLinks as $link) {
            DB::table('work_links')->insert([
                'work_id' => $work->id,
                'title' => $link->title,
                'link' => $link->url,
            ]);
        }

        //Third Step: Save all the covers
        foreach ($workInfo->covers as $cover) {
            DB::table('work_covers')->insert([
                'work_id' => $work->id,
                'cover' => $cover,
            ]);
        }

        //Fourth step: Save all the optional subject People
        $subjectPeoples = $workInfo->subject_people ?? [];
        foreach ($subjectPeoples as $subjectPeople) {
            DB::table('work_subject_peoples')->insert([
                'work_id' => $work->id,
                'people' => $subjectPeople,
            ]);
        }

        //Fifth Step: Save all the subject
        $subjects = $workInfo->subjects ?? [];
        foreach ($subjects as $subject) {
            DB::table('work_subjects')->insert([
                'work_id' => $work->id,
                'subject' => $subject,
            ]);
        }

        //SESTO(?) Step: Save all the optional subject times
        $subjectTimes = $workInfo->subject_times ?? [];
        foreach ($subjectTimes as $subjectTime) {
            DB::table('work_subject_times')->insert([
                'work_id' => $work->id,
                'time' => $subjectTime,
            ]);
        }

        //SETTIMO(?) Step: Save all the optional subject places
        $subjectPlaces = $workInfo->subject_places ?? [];
        foreach ($subjectPlaces as $subjectPlace) {
            DB::table('work_subject_places')->insert([
                'work_id' => $work->id,
                'place' => $subjectPlace,
            ]);
        }

        //Then is time for the authors section
        $authors = $workInfo->authors ?? [];
        foreach ($authors as $workAuthor) {

            $openLibraryAuthorID = str_replace('/authors/', '', $workAuthor->author->key);

            //First of all i need to check if the given author is already present in the author table
            $savedAuthor = Author::where('openlibrary_author_id', $openLibraryAuthorID)->first();
            if (! empty($savedAuthor)) {
                $work->authors()->attach($savedAuthor->id);

                continue;
            }

            //If not the first thing to do is to go and get all the author info
            $authorInfo = $openLibraryClass->getAuthorInfo($openLibraryAuthorID);

            //Create a record for the new author
            $author = Author::create([
                'openlibrary_author_id' => $openLibraryAuthorID,
                'name' => $authorInfo->name,
                'bio' => $authorInfo->bio,
                'birth_date' => date('Y-m-d', strtotime($authorInfo->birth_date)) ?? null,
                'death_date' => date('Y-m-d', strtotime($authorInfo->death_date)) ?? null,
            ]);

            //Save all the author photos
            foreach ($authorInfo->photos as $photo) {

                // For some reason some of the photos are -1 from the API endpoint
                if ($photo == -1) {
                    continue;
                }

                DB::table('author_photos')->insert([
                    'author_id' => $author->id,
                    'photo_id' => $photo,
                ]);
            }

            //Save all the author alternative names
            foreach ($authorInfo->alternate_names as $alternate_name) {
                DB::table('author_alternative_names')->insert([
                    'author_id' => $author->id,
                    'name' => $alternate_name,
                ]);
            }

            //Save all the useful links
            $usefulLinks = $authorInfo->links ?? [];
            foreach ($usefulLinks as $link) {
                DB::table('author_useful_links')->insert([
                    'author_id' => $author->id,
                    'title' => $link->title,
                    'link' => $link->url,
                ]);
            }

            $work->authors()->attach($author->id);

        }

        Review::where('id', $this->reviewID)->update(['review_status' => 'DONE']);

    }
}
