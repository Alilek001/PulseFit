<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'calories',
        'proteins',
        'carbs',
        'fats',
        'description',
        'image_url',
        'is_premium',
    ];
}
