<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'amount',
        'status',
        'card_number',
        'card_holder',
    ];

}
