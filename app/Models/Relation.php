<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Relation extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'icon'
    ];

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'relation_clients', 'relation_id', 'client_id')->withtimestamps();
    }
}

