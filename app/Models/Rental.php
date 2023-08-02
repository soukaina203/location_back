<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'car_id', 'rental_start', 'rental_end',
        'total_price', 'hourFinish','hourStart','currency'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

}
