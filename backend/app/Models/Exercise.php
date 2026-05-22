<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'muscle_group',
        'description',
        'video_url',
        'is_premium',
    ];

    public function routines()
    {
        return $this->belongsToMany(Routine::class)->withPivot('sets', 'reps', 'notes')->withTimestamps();
    }
}
