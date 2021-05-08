<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lycra extends Model
{
    use HasFactory;

    protected $fillable = [
        'color',
        'name',
    ];

    public function competitions()
    {
        return $this->belongsToMany(Competition::class, 'lycra_competition');
    }
}
