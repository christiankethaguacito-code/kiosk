<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Head extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'credentials'
    ];

    /**
     * Get the office led by this head
     */
    public function office()
    {
        return $this->hasOne(Office::class);
    }
}
