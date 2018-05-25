<?php

namespace App\Models\Auth;

use AuthSlim\User\Models\User as UserModel;
use Illuminate\Database\Eloquent\Model;

class User extends UserModel
{
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'auth_token'];
}