<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipment extends Model
{
    use HasFactory, Notifiable;

     protected $fillable = [
        'name',
        'description',
        'dailyPrice',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sport()
    {
        return $this->belongsToMany(
            Sport::class,
            'equipment_sport',
            'equipment_id',
            'sportId'
        );
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'equipment_id');
    }

}
