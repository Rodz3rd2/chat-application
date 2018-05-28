<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['message', 'sender_id', 'receiver_id', 'is_read'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', "sender_id");
    }

    /**
     * Use on twig view
     * @return [object] Conversation of two users
     */
    public static function conversation()
    {
        $args = func_get_args();
        list($sender_id, $receiver_id) = $args[0];

        return static::where('sender_id', $sender_id)
                ->orWhere('receiver_id', $sender_id)
                ->orWhere('sender_id', $receiver_id)
                ->orWhere('receiver_id', $receiver_id)
                ->get();
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
}