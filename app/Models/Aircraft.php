<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    use HasFactory;

    public $table = "aircrafts";

    protected $fillable = [
        'registration',
        'model',
        'seat_config',
        'total_seat',
    ];

    public function flights()
    {
        return $this->belongsToMany(Flight::class, 'aircraft_flights');
    }
}
