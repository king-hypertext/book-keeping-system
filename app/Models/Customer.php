<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = "customers";
    protected $fillable = [
        'name',
        'date_of_birth',
        'phone',
        'card_number',
        'next_of_king',
        'daily_payable_amount'
    ];
}
