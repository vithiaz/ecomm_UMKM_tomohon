<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefoundOrder extends Model
{
    use HasFactory;

    protected $table = 'refound_orders';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'order_id',
        'bank_name',
        'account_number',
        'account_name',
        'payment_status',
        'refound_description',
    ];



}
