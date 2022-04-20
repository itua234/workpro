<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNextOfKin extends Model
{
    use HasFactory, BelongsToUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'date_of_birth',
        'relationship',
        'house_address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];



    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    public function setFirstnameAttribute($firstname)
    {
        $this->attributes['firstname'] = ucwords(strtolower($firstname));
    }

    public function setLastnameAttribute($lastname)
    {
        $this->attributes['lastname'] = ucwords(strtolower($lastname));
    }
}
