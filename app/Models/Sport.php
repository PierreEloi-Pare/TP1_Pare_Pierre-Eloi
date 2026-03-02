<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sport extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
    ];

    public function equipment()
    {
        return $this->belongsToMany(
            Equipment::class,
            'equipment_sport',
            'sport_id',
            'equipment_id'
        );
    }
}
