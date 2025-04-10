<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matching extends Model
{
    protected $fillable = [
        'client_id',
        'client_id_other'
    ];

    public function clients(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
