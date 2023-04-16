<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BPayment;


class Bill extends Model
{
    use HasFactory;


    protected $fillable = [
        'user',
        'bill',
        'comments',
        'status',
    ];

    public function bpayments()
    {
        return $this->hasMany(BPayment::class, 'bill');
    }

}
