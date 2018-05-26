<?php

namespace App\Models;

use AuthSlim\User\Auth\Auth;
use AuthSlim\User\Models\User as UserModel;
use Illuminate\Database\Eloquent\Model;

class User extends UserModel
{
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'picture', 'auth_token'];

    public function messages()
    {
        return $this->hasMany('App\Models\Message', "from_user_id");
    }

    public function messagesTo($user_id)
    {
        return $this->messages()
            ->where('to_user_id', $user_id)
            ->orWhere('from_user_id', $user_id)
            ->orderBy('created_at', "ASC");
    }

    public static function contacts()
    {
        return static::where('id', "<>", Auth::user()->id)->get();
    }
}