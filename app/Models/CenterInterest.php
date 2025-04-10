<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CenterInterest extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
    ];

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'client_center_interests', 'center_interest_id', 'client_id')->withtimestamps();
    }
}
