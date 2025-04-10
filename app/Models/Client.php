<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'user_id',
        'size',
        'gender',
        'interestBy',
        'adress',
        'phone',
        'birth',
        'isDeleted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function matchings(): HasMany
    {
        return $this->hasMany(Matching::class);
    }


    public function centerInterests(): BelongsToMany
    {
        return $this->belongsToMany(CenterInterest::class, 'client_center_interests', 'client_id', 'center_interest_id')->withtimestamps();
    }

    public function relations(): BelongsToMany
    {
        return $this->belongsToMany(Relation::class, 'relation_clients', 'client_id', 'relation_id')->withtimestamps();
    }

}
