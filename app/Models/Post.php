<?php

namespace App\Models;

use App\Traits\HasMedia;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    use HasMedia;
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'is_published',

    ];
    public static function getSlug(): string
    {
        return 'title';
    }
}
