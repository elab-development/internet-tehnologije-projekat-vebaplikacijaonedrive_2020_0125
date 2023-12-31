<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    use HasFactory;

    protected $primaryKey = 'PIB';
    public $timestamps = false;
    public $incrementing = false;
    
    public function member()
    {
        return $this->hasMany(Member::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
