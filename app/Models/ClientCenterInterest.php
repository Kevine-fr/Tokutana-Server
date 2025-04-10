<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCenterInterest extends Model
{
    protected $fillable = [
        'client_id',
        'center_interest_id',
    ];
}
