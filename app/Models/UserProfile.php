<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory, BelongsToUser;

    protected $fillable = [
        'user_id',
        'nationality_id',
        'date_of_birth'
    ];
}
