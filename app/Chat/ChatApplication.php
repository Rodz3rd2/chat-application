<?php

namespace App\Chat;

use App\Models\Message;
use App\Models\User;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Session;

class ChatApplication implements MessageComponentInterface {
    protected $clients;

    const SENDING_MESSAGE_TYPE = "sending-message";
    const TYPING_TYPE = "typing";

    public function __construct() {
        $this->clients = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        parse_str($conn->httpRequest->getUri()->getQuery(), $params);
        $this->clients[$params['auth_id']] = $conn;

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        parse_str($from->httpRequest->getUri()->getQuery(), $params);
        $data = json_decode($msg);
        $sender_id = $params['auth_id'];
        $receiver_id = $data->receiver_id;

        switch ($data->type) {
            case static::TYPING_TYPE:
                // if receiver online
                if (isset($this->clients[$receiver_id]))
                {
                    $receiver = $this->clients[$receiver_id];
                    $receiver->send(json_encode($data));
                }

                break;

            case static::SENDING_MESSAGE_TYPE:
                $message = Message::sendMessage($data->message, $sender_id, $receiver_id);
                $user_sender = User::find($sender_id);
                $user_receiver = User::find($receiver_id);

                if (!is_null($message))
                {
                    // self
                    $message_receive['type'] = static::SENDING_MESSAGE_TYPE;

                    $message_receive['sender'] = [
                        'message' => $data->message,
                        'receiver_id' => $receiver_id,
                        'picture' => $user_sender->picture
                    ];
                    $from->send(json_encode($message_receive));
                    unset($message_receive['sender']);

                    // if receiver online
                    if (isset($this->clients[$receiver_id]))
                    {
                        $receiver = $this->clients[$receiver_id];

                        $message_receive['receiver'] = [
                            'message' => $data->message,
                            'sender_id' => $sender_id,
                            'picture' => $user_sender->picture,
                            'number_unread' => $user_receiver->numberOfUnread($sender_id)
                        ];

                        $receiver->send(json_encode($message_receive));
                        unset($message_receive['receiver']);
                    }
                }

                break;
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $key = array_search($conn, $this->clients);
        unset($this->clients[$key]);

        echo "Clients number: " . count($this->clients) . PHP_EOL;
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}