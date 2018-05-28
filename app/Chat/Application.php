<?php

namespace App\Chat;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Application extends Events implements MessageComponentInterface {

    public function __construct() {
        parent::__construct();
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

        $data = json_decode($msg);
        $event = $data->event;
        unset($data->event);

        $this->{$event}($from, $data);
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $key = array_search($conn, $this->clients);
        unset($this->clients[$key]);

        $this->onDisconnect($conn, "");

        echo "Clients number: " . count($this->clients) . PHP_EOL;
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}