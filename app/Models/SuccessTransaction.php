<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessTransaction extends Model
{
    use HasFactory;

    protected $table = 'success_transactions';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'order_id',
        'seller_payment_status',
    ];

}
