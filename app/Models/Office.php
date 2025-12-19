<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'building_id',
        'name',
        'floor_number',
        'head_name',
        'head_title',
        'head_image'
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}

