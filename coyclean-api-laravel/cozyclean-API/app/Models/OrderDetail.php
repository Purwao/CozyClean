<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable=[
        'orders_id',
        'services_id',
        'order_code',
        'types_id',
        'weight',
    ];
}
