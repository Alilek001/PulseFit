<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'calories',
        'proteins',
        'carbs',
        'fats',
        'description',
        'is_premium',
    ];
}
