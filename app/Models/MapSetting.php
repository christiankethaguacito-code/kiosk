<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapSetting extends Model
{
    protected $fillable = [
        'map_image_path',
        'kiosk_x',
        'kiosk_y'
    ];

    protected $casts = [
        'kiosk_x' => 'integer',
        'kiosk_y' => 'integer'
    ];
}
