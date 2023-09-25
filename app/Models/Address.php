<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    // Fillable

    protected $fillable = ["home_address", "latitude", "longitude"];

    // Funzione per richiamare la casa

    public function house()
    {
        return $this->hasOne(House::class);
    }
}
