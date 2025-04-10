<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelationClient extends Model
{
    protected $fillable = [
        'client_id',
        'relation_id'
    ];
}
