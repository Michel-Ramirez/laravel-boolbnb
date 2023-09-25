<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ["name", "icon"];

    // Funzione per richiamare le case

    public function houses()
    {
        return $this->belongsToMany(House::class);
    }
}
