<?php

namespace App\Models\Auth;

use AuthSlim\User\Auth\Auth;
use AuthSlim\User\Models\User as UserModel;
use Illuminate\Database\Eloquent\Model;

class User extends UserModel
{
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'picture', 'auth_token'];

    public static function contacts()
    {
        return static::where('id', "<>", Auth::user()->id)->get();
    }
}