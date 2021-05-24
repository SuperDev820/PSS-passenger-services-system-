<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    public $table = "flights";

    protected $fillable = [
        'aircraft_id',
        'airline_code',
        'origin_airport_name',
        'origin_airport_code',
        'destination_airport_name',
        'destination_airport_code',
        'departure_time',
        'arrival_time',
        'flight_time',
        'status',
    ];

    protected $casts = [
        'departure_time' => 'datetime:H:i', // Change your format
        'arrival_time' => 'datetime:H:i',
    ];

    public function aircraft()
    {
        return $this->belongsTo(Aircraft::class);
    }
}
