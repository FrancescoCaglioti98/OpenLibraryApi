<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function work(): HasOne
    {
        return $this->hasOne(Work::class, 'openlibrary_work_id', 'openlibrary_work_id');
    }

}
