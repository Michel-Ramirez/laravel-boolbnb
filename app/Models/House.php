<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class House extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ["user_id", "address_id", "name", "type", "description", "night_price", "total_bath", "total_rooms", "total_beds", "mq", "photo", "is_publish"];

    // Funzione per richiamare l'utente

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Funzione per richiamare i messaggi

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Funzione per richiamare le visualizzazioni

    public function views()
    {
        return $this->hasMany(View::class);
    }

    // Funzione per richiamare l'indirizzo

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // Funzione per richiamare i servizi

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    // Funzione per richiamare gli sponsor

    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class)->withPivot('sponsor_start', 'sponsor_end');
    }

    // Funzione per richiamare le foto

    public function photoes()
    {
        return $this->hasMany(Photo::class);
    }
}
