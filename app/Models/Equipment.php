<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
     protected $fillable = [
        'name',
        'description',
        'dailyPrice',
        'categoryId',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId');
    }

    public function sports()
    {
        return $this->belongsToMany(
            Sport::class,
            'equipment_sport',
            'equipmentId',
            'sportId'
        );
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'equipmentId');
    }
}
