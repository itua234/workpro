<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory, BelongsToUser;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'balance'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'wallet_pin'
    ];

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
