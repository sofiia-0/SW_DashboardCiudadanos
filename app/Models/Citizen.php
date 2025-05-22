<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'city_id',
        'address',
        'phone'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
