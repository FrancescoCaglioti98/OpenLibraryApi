<?php

namespace App\Models;

use App\Classes\OpenLibraryClass;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Author extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'authors';

    protected $fillable = [
        'openlibrary_author_id',
        'name',
        'bio',
        'birth_date',
        'death_date',
    ];

    public function photos(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {

                $photos = DB::table('author_photos')
                    ->where('author_id', $attributes['id'])
                    ->get();

                $returnPhotos = [];
                foreach ($photos as $photo) {
                    $photoSize = [];
                    foreach (OpenLibraryClass::$imageSize as $key => $size) {
                        $photoSize[$key] = OpenLibraryClass::$coverLink.$photo->photo_id.'-'.$size.OpenLibraryClass::$imageExtension;
                    }
                    $returnPhotos[] = $photoSize;
                }

                return $returnPhotos;
            }
        );
    }

    public function alternativeNames(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {

                $names = DB::table('author_alternative_names')
                    ->where('author_id', $attributes['id'])
                    ->get();

                $returnNames = [];
                foreach ($names as $name) {
                    $returnNames[] = $name;
                }

                return $returnNames;
            }
        );
    }

    public function usefulLinks(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {

                $links = DB::table('author_useful_links')
                    ->where('author_id', $attributes['id'])
                    ->get();

                $returnLinks = [];
                foreach ($links as $link) {
                    $returnLinks[] = [
                        'title' => $link->title,
                        'link' => $link->link,
                    ];
                }

                return $returnLinks;
            }
        );
    }

    public function works(): BelongsToMany
    {
        return $this->belongsToMany(Work::class);
    }

    public function getGeneralInfo(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'openlibrary_id' => $this->openlibrary_author_id,
            'link' => $_ENV['APP_URL'].'/api/author/' . $this->id,
        ];
    }

}
