<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['house_id', "img"];

    public function house()
    {
        return $this->belongsTo(House::class);
    }


}
