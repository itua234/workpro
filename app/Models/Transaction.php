<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'amount',
        'transaction_type'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function wallet(){
        return $this->belongsTo(Wallet::class);
    }

}
