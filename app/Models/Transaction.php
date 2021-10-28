<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\HasProfilePhoto;

class Transaction extends Model
{
    use HasFactory;
    use HasProfilePhoto;

    protected $fillable = [
        'user',
        'amount',
        'remaining_balance',
        'category',
        'description',
        'photo',
        'start_date',
        'due_date',
        'suggested_dates',
        'suggested_amounts',
        // 'zelle',
    ];

}