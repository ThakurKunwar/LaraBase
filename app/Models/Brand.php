<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Override;

class Brand extends Model
{
    //
    use HasSlug;
    protected $fillable =
    [
        'name',
        'slug',
        'country',
        'is_active'
    ];

    #[Override]
    public function casts()
    {
        return [
            'is_Active' => 'boolean',
        ];
    }
}
