<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmkmRegistration extends Model
{
    use HasFactory;

    protected $table = 'umkm_registrations';

    protected $fillable = [
        'user_id',
        'status',
        'message',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
