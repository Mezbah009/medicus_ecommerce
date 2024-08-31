<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    protected $table = 'user_otp';
    protected $fillable = [
        'phone',
        'otp',
        'expire_at',
    ];
    use HasFactory;
}
