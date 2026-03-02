<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
     protected $fillable = [
        'startDate',
        'endDate',
        'totalPrice',
        'userId',
        'equipmentId',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipmentId');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'rentalId');
    }
}
