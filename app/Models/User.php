<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Umkm;
use App\Models\BankAccount;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_type',
        'username',
        'first_name',
        'last_name',
        'password',
        'email',
        'phone_number',
        'umkm_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bank_accounts()
    {
        return $this->hasMany(BankAccount::class, 'user_id', 'id');
    }

    public function umkms()
    {
        return $this->hasMany(Umkm::class, 'user_id', 'id');
    }
}
