<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firma extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    public function zaposleni()
    {
        return $this->hasMany(Zaposleni::class);
    }
}
