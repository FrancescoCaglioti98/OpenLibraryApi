<?php

namespace App\Models;

use App\Classes\OpenLibraryClass;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Work extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = "works";
    protected $fillable = [
        'openlibrary_work_id',
        'title',
        'description',
        'first_publish'
    ];


    public function links(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {

                $links = DB::table('work_links')
                    ->where('work_id', $attributes["id"])
                    ->get();

                $returnLinks = [];
                foreach ($links as $link) {
                    $returnLinks[] = [
                        "title" => $link->title,
                        "link" => $link->link
                    ];
                }
                return $returnLinks;
            }
        );
    }

    public function covers(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {

                $covers = DB::table('work_covers')
                    ->where('work_id', $attributes["id"])
                    ->get();

                $returnCovers = [];
                foreach ($covers as $cover) {
                    $singleCover = [];
                    foreach (OpenLibraryClass::$coverSizes as $key => $coverSize) {
                        $singleCover[$key] = OpenLibraryClass::$coverLink . $cover->cover . "-" . $coverSize . OpenLibraryClass::$coverExtension;
                    }
                    $returnCovers[] = $singleCover;
                }
                return $returnCovers;
            }
        );

    }

    public function subjectPeople(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {

                $subjectPeoples = DB::table('work_subject_peoples')
                    ->where('work_id', $attributes["id"])
                    ->get();

                $returnSubjectPeoples = [];
                foreach ($subjectPeoples as $subjectPeople) {
                    $returnSubjectPeoples[] = $subjectPeople->people;
                }
                return $returnSubjectPeoples;
            }
        );
    }

    public function subject(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {

                $subjects = DB::table('work_subjects')
                    ->where('work_id', $attributes["id"])
                    ->get();

                $returnSubjects = [];
                foreach ($subjects as $subject) {
                    $returnSubjects[] = $subject->subject;
                }
                return $returnSubjects;
            }
        );

    }

    public function subjectTimes(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {

                $subjectTimes = DB::table('work_subject_times')
                    ->where('work_id', $attributes["id"])
                    ->get();

                $returnSubjectTimes = [];
                foreach ($subjectTimes as $subjectTime) {
                    $returnSubjectTimes[] = $subjectTime->time;
                }
                return $returnSubjectTimes;
            }
        );
    }

    public function subjectPlaces(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {

                $subjectPlaces = DB::table('work_subject_places')
                    ->where('work_id', $attributes["id"])
                    ->get();

                $returnSubjectPlaces = [];
                foreach ($subjectPlaces as $subjectPlace) {
                    $returnSubjectPlaces[] = $subjectPlace->place;
                }
                return $returnSubjectPlaces;
            }
        );
    }

}
