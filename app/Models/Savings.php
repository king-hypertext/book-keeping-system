<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Savings extends Model
{
    use HasFactory;
    protected $table = "savings";
    protected $fillable = [
        'customer_id',
        'card_number',
        'deposit_amount',
        'withdrawal_amount',
        'date',
        'trans_type',
    ];

}
