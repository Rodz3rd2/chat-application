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
        return $this->hasMany('App\Models\Message', "sender_id");
    }

    public function numberOfUnread($sender_id)
    {
        return $this->messages()
                    ->where('sender_id', $sender_id)
                    // ->where('receiver_id', $this->id)
                    ->get()
                    ->count();
    }

    public static function contacts()
    {
        return static::where('id', "<>", Auth::user()->id)->get();
    }
}