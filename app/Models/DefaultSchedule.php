<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user',
        'payment_length',
        'payment_months',
        'date',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];

}
