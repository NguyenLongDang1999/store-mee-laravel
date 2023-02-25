<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'slider';

    protected $fillable = [
        'name',
        'url',
        'description',
        'image_uri',
        'status',
        'start_date',
        'end_date',
    ];
}
