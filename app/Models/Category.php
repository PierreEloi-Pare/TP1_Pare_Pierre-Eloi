<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, Notifiable;

   protected $fillable = [
        'name',
    ];

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'category_id');
    }
}
