<?php

namespace App\Models;

use App\Models\ChatStatus;
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

    public function chatStatus()
    {
        return $this->hasOne('App\Models\ChatStatus');
    }

    public function numberOfUnread($receiver_id)
    {
        return $this->messages()
                    ->where('receiver_id', $receiver_id)
                    ->where('is_read', 0)
                    ->get()
                    ->count();
    }

    public static function contacts()
    {
        return static::where('id', "<>", Auth::user()->id);
    }

    public static function contactsOrderByOnlineStatus()
    {
        return static::select(['users.*', 'chat_statuses.status'])
            ->where('users.id', "<>", Auth::user()->id)
            ->leftJoin('chat_statuses', "users.id", "=", "chat_statuses.user_id")
            ->orderByRaw('FIELD(chat_statuses.status, "offline", "online") DESC');
    }
}