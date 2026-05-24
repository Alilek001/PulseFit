<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyPlanItem extends Model
{
    protected $fillable = ['user_id', 'routine_id', 'day_of_week'];

    public function routine()
    {
        return $this->belongsTo(Routine::class)->with('exercises');
    }
}
