<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'image_path',
        'image_gallery',
        'map_x',
        'map_y',
        'endpoint_x',
        'endpoint_y',
        'road_connection'
    ];

    protected $casts = [
        'image_gallery' => 'array',
    ];

    public function offices()
    {
        return $this->hasMany(Office::class);
    }
}

