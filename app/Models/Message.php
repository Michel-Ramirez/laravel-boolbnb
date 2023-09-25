<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ["house_id", "name", "email", "message"];

    // Funzione per richiamare la casa

    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
