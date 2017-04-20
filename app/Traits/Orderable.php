<?php

namespace App\Traits;


trait Orderable
{
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
    }

    public function scopeOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    public function scopeRecentlyUpdated($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }
}