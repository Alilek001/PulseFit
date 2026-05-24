<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutLog extends Model
{
    protected $fillable = ['user_id', 'routine_id', 'routine_name', 'duration_minutes', 'notes', 'logged_at'];

    protected $casts = ['logged_at' => 'date:Y-m-d'];
}
