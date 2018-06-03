<?php

namespace App\Chat;

use App\Models\ChatStatus;
use App\Models\Message;
use App\Models\User;
use Ratchet\ConnectionInterface;

class Events
{
    protected $clients;

    public function __construct() {
        $this->clients = [];
    }

    public function onConnectionEstablish(ConnectionInterface $from, $data)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);
        $auth_id = $params['auth_id'];

        $clients = $this->clients;

        foreach ($clients as $client) {
            if ($client !== $from)
            {
                $return_data['event'] = __FUNCTION__;
                $return_data['user_id'] = $auth_id;

                // if user have no chat status
                $chatStatus = ChatStatus::findByUserId($auth_id);
                $result = !is_null($chatStatus) ? $chatStatus->setAsOnline() : ChatStatus::createOnlineUser($auth_id);

                $return_data['result'] = !is_null($result);

                $client->send(json_encode($return_data));
            }
        }
    }

    public function onDisconnect(ConnectionInterface $from, $data)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);
        $auth_id = $params['auth_id'];

        $clients = $this->clients;

        foreach ($clients as $client) {
            if ($client !== $from)
            {
                $return_data['event'] = __FUNCTION__;
                $return_data['user_id'] = $auth_id;

                // if user have no chat status
                $chatStatus = ChatStatus::findByUserId($auth_id);
                $result = !is_null($chatStatus) ? $chatStatus->setAsOffline() : ChatStatus::createOnlineUser($auth_id);

                $return_data['result'] = !is_null($result);

                $client->send(json_encode($return_data));
            }
        }
    }

    public function onSendMessage(ConnectionInterface $from, $data)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $sender_id = $params['auth_id'];
        $receiver_id = $data->receiver_id;

        $message = Message::sendMessage($data->message, $sender_id, $receiver_id);
        $user_sender = User::find($sender_id);
        $user_receiver = User::find($receiver_id);

        if (!is_null($message))
        {
            $message = Message::messageWithSenderAndReceiver($message->id);

            // self
            $return_data['event'] = __FUNCTION__;
            $return_data['sender_id'] = $sender_id;

            $return_data['sender'] = [
                'message' => $message
            ];

            $from->send(json_encode($return_data));
            unset($return_data['sender']);

            // if receiver online
            if (isset($this->clients[$receiver_id]))
            {
                $receiver = $this->clients[$receiver_id];

                $return_data['receiver'] = [
                    'message' => $message,
                    'number_unread' => $user_sender->numberOfUnread($receiver_id)
                ];

                $receiver->send(json_encode($return_data));
                unset($return_data['receiver']);
            }
        }
    }

    public function onTyping(ConnectionInterface $from, $data)
    {
        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        // mark unread as read
        $data->sender_id = $data->receiver_id;
        $this->onReadMessage($from, $data);

        // if receiver online
        if (isset($this->clients[$data->receiver_id]))
        {
            $return_data = [
                'event' => __FUNCTION__,
                'sender_id' => $params['auth_id']
            ];

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

    public function onFetchMessages(ConnectionInterface $from, $data)
    {
        $this->onReadMessage($from, $data);

        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $auth_id = $params['auth_id'];
        $sender_id = $data->sender_id;

        $conversation = Message::conversation([$sender_id, $auth_id])
                            ->with(['sender', 'receiver'])
                            ->orderBy('id', "DESC")
                            ->limit(Message::DEFAULT_CONVERSATION_LENGTH)
                            ->get()
                            ->sortBy('id');

        // echo "<pre>";
        // var_dump($conversation->pluck('id'));

        $return_data['event'] = __FUNCTION__;
        $return_data['conversation'] = $conversation;

        $from->send(json_encode($return_data));
    }

    public function onLoadMoreMessages(ConnectionInterface $from, $data)
    {
        $this->onReadMessage($from, $data);

        parse_str($from->httpRequest->getUri()->getQuery(), $params);

        $auth_id = $params['auth_id'];
        $sender_id = $data->sender_id;
        $load_more_increment = $data->load_more_increment;

        $conversation = Message::conversation([$sender_id, $auth_id])
                            ->with(['sender', 'receiver'])
                            ->orderBy('id', "DESC")
                            ->offset(Message::DEFAULT_CONVERSATION_LENGTH * $load_more_increment)
                            ->limit(Message::DEFAULT_CONVERSATION_LENGTH)
                            ->get()
                            ->sortBy('id');

        // echo "<pre>";
        // var_dump($conversation->pluck('id'));

        $return_data['event'] = __FUNCTION__;
        $return_data['conversation'] = $conversation;

        $from->send(json_encode($return_data));
    }
}