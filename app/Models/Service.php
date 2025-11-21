<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'office_id',
        'description'
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
