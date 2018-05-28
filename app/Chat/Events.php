<?php

namespace App\Chat;

use App\Models\Message;
use App\Models\User;
use Ratchet\ConnectionInterface;

class Events
{
    protected $clients;

    public function __construct() {
        $this->clients = [];
    }

    public function onSendMessage(ConnectionInterface $from, $data)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $sender_id = $params['auth_id'];
        $receiver_id = $data->receiver_id;

        $is_sent = Message::sendMessage($data->message, $sender_id, $receiver_id);
        $user_sender = User::find($sender_id);
        $user_receiver = User::find($receiver_id);

        if (!is_null($is_sent))
        {
            // self
            $return_data['event'] = __FUNCTION__;

            $return_data['sender'] = [
                'message' => $data->message,
                'receiver_id' => $receiver_id,
                'picture' => $user_sender->picture
            ];
            $from->send(json_encode($return_data));
            unset($return_data['sender']);

            // if receiver online
            if (isset($this->clients[$receiver_id]))
            {
                $receiver = $this->clients[$receiver_id];

                $return_data['receiver'] = [
                    'message' => $data->message,
                    'sender_id' => $sender_id,
                    'picture' => $user_sender->picture,
                    'number_unread' => $user_sender->numberOfUnread($receiver_id)
                ];

                $receiver->send(json_encode($return_data));
                unset($return_data['receiver']);
            }
        }
    }

    public function onTyping(ConnectionInterface $from, $data)
    {
        // mark unread as read
        $data->sender_id = $data->receiver_id;
        $this->onReadMessage($from, $data);

        // if receiver online
        if (isset($this->clients[$data->receiver_id]))
        {
            $return_data['event'] = __FUNCTION__;

            $receiver = $this->clients[$data->receiver_id];
            $receiver->send(json_encode($return_data));
        }
    }

    public function onStopTyping(ConnectionInterface $from, $data)
    {
        // if receiver online
        if (isset($this->clients[$data->receiver_id]))
        {
            $return_data['event'] = __FUNCTION__;

            $receiver = $this->clients[$data->receiver_id];
            $receiver->send(json_encode($return_data));
        }
    }

    public function onReadMessage(ConnectionInterface $from, $data)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $receiver_id = $params['auth_id'];
        $sender_id = $data->sender_id;

        $is_marked = Message::markAsRead($sender_id, $receiver_id);
        if (!is_null($is_marked))
        {
            $return_data['event'] = __FUNCTION__;
            $return_data['sender_id'] = $sender_id;

            $from->send(json_encode($return_data));
        }
    }
}