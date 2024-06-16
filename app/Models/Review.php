<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    public $timestamps = true;

    protected $fillable = [
        'openlibrary_work_id',
        'review',
        'score',
        'review_status',
    ];

}
