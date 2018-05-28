<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatStatus extends Model
{
    const ONLINE_STATUS = "online";
    const OFFLINE_STATUS = "offline";

    protected $fillable = ["status", "user_id"];

    public function setAsOnline()
    {
        $this->status = static::ONLINE_STATUS;
        return $this->save();
    }

    public function setAsOffline()
    {
        $this->status = static::OFFLINE_STATUS;
        return $this->save();
    }

    public function isOnline()
    {
        return $this->status === static::ONLINE_STATUS;
    }

    public function isOffline()
    {
        return $this->status === static::OFFLINE_STATUS;
    }

    public static function findByUserId($user_id)
    {
        return static::where('user_id', $user_id)->first();
    }

    public static function createOnlineUser($user_id)
    {
        return static::create([
            'status' => ChatStatus::ONLINE_STATUS,
            'user_id' => $user_id
        ]);
    }
}