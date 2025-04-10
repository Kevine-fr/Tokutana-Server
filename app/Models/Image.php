<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $fillable = [
        'client_id',
        'path',
        'isDeleted',
    ];

    public function clients(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
