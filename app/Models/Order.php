<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'order_date',
        'total_price',
        'status',
        'details_json',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'details_json' => 'array',
    ];
}
