<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'tier',
        'description',
        'training_days',
        'is_premium',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'routine_exercise')->withPivot('sets', 'reps', 'notes')->withTimestamps();
    }
}
