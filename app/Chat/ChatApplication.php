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

        switch ($data->type) {
            case static::TYPING_TYPE:
                # code...
                break;

            case static::SENDING_MESSAGE_TYPE:
                $message = Message::sendMessage($data->message, $params['auth_id'], $data->to_user_id);
                $user_sender = User::find($params['auth_id']);

                if (!is_null($message))
                {
                    // self
                    $message_receive['type'] = static::SENDING_MESSAGE_TYPE;

                    $message_receive['sender'] = [
                        'message' => $data->message,
                        'to_user_id' => $data->to_user_id,
                        'picture' => $user_sender->picture
                    ];
                    $from->send(json_encode($message_receive));
                    unset($message_receive['sender']);

                    // if receiver online
                    if (isset($this->clients[$data->to_user_id]))
                    {
                        $receiver = $this->clients[$data->to_user_id];

                        $message_receive['receiver'] = [
                            'message' => $data->message,
                            'from_user_id' => $params['auth_id'],
                            'picture' => $user_sender->picture
                        ];

                        $receiver->send(json_encode($message_receive));
                        unset($message_receive['receiver']);
                    }
                }

                break;
        }

        // foreach ($this->clients as $client) {
        //     if ($from !== $client) {
        //         // The sender is not the receiver, send to each client connected
        //         // $client->send($msg);

        //         $data = json_decode($msg);
        //         $message = Message::sendMessage($data->message, Auth::user()->id, $data->to_user_id);
        //     }
        // }
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