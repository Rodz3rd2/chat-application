<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['message', 'from_user_id', 'to_user_id', 'is_read'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', "from_user_id");
    }

    public static function sendMessage($message, $from_user_id, $to_user_id)
    {
        return static::create([
            'message' => $message,
            'from_user_id' => $from_user_id,
            'to_user_id' => $to_user_id
        ]);
    }
}