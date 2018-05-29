<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['message', 'sender_id', 'receiver_id', 'is_read'];

    public function sender()
    {
        return $this->belongsTo('App\Models\User', "sender_id");
    }

    public function receiver()
    {
        return $this->belongsTo('App\Models\User', "receiver_id");
    }

    /**
     * [conversation of two users]
     * @param  [array] [0 => [$sender_id, $receiver_id]]
     * @return [collection] [description]
     */
    public static function conversation()
    {
        $args = func_get_args();
        list($sender_id, $receiver_id) = $args[0];

        return static::where('sender_id', $sender_id)
                ->where('receiver_id', $receiver_id)
                ->orWhere('sender_id', $receiver_id)
                ->where('receiver_id', $sender_id);
    }

    public static function sendMessage($message, $sender_id, $receiver_id)
    {
        return static::create([
            'message' => $message,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id
        ]);
    }

    public static function markAsRead($sender_id, $receiver_id)
    {
        return static::where('sender_id', $sender_id)
                    ->where('receiver_id', $receiver_id)
                    ->update(['is_read' => 1]);
    }

    public static function messageWithSenderAndReceiver($id)
    {
        return static::with(['sender', 'receiver'])
                    ->where('id', $id)
                    ->first();
    }
}