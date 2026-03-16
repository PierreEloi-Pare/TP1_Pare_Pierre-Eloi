<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory, Notifiable;
     protected $fillable = [
        'rating',
        'comment',
        'user_id',
        'rental_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id');
    }
}
