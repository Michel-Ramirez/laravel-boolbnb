<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = ["name", "duration", "price"];

    // Funzione per richiamare le case

    public function houses()
    {
        return $this->belongsToMany(House::class);
    }
}
