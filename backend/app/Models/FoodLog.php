<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodLog extends Model
{
    protected $fillable = ['user_id', 'name', 'calories', 'proteins', 'carbs', 'fats', 'date'];
}
