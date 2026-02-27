<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Link extends Model
{
    protected $fillable = [
        'name',
        'url',
        'year',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'year' => 'integer',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
