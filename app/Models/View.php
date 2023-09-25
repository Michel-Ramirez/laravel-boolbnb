<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    protected $fillable = ["house_id", "view_date", "ip_viewer"];

    // Funzione per richiamare la casa

    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
