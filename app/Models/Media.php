<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    //
    protected $fillable = [
        'mediaable_id',
        'mediaable_type',
        'property',
        'path',
        'order'
    ];
    public function casts()
    {
        return [
            'property' => 'array'
        ];
    }
    public function mediaable()
    {
        return $this->morphTo();
    }
    public function getFullUrlAttribute()
    {
        return Storage::url($this->path);
    }

    protected $appends = ['full_url'];
}
