<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $fillable = [
        'name',
    ];

    public function equipment()
    {
        return $this->belongsToMany(
            Equipment::class,
            'equipment_sport',
            'sportId',
            'equipmentId'
        );
    }
}
