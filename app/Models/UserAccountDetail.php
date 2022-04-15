<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccountDetail extends Model
{
    use HasFactory, BelongsToUser;

    protected $fillable = [
        'user_id',
        'bank_id',
        'account_number'
    ];
}
