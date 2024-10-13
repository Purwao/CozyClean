<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=[
        'users_id',
        'order_date',
        'status',
        'total',
        'paid',
        'change'
    ];

    public function user()
{
    return $this->belongsTo(User::class, 'users_id');
}

public function service()
{
    return $this->belongsTo(Service::class, 'services_id');
}


}
