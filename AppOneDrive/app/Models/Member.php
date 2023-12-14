<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $primaryKey = ['user_id', 'firma_pib'];
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'firm_pib',
        'AddedAt',
        'privileges',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }
}
