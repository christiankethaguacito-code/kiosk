<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image_path',
        'display_order',
        'starts_at',
        'ends_at',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime'
    ];

    /**
     * Scope to get only active and non-expired announcements
     * Filters by: is_active = true, starts_at <= now, ends_at >= now (or null)
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', now());
            });
    }

    /**
     * Check if announcement is currently valid (not expired)
     */
    public function isValid()
    {
        $now = now();
        
        if ($this->ends_at && $this->ends_at < $now) {
            return false;
        }
        
        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }
        
        return $this->is_active;
    }

    /**
     * Check if announcement is expired
     */
    public function isExpired()
    {
        return $this->ends_at && $this->ends_at < now();
    }
}
