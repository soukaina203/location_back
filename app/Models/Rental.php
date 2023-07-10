<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'car_id', 'rental_start', 'rental_end',
        'total_price', 'location'
    ];
}
