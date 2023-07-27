<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = ['model', 'make', 'year',
     'color', 'price_per_day','photo','type',
     'available'];
     public function rentals()
     {
         return $this->hasMany(Rental::class, 'car_id');
     }
}
