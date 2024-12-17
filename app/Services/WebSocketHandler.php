<?php

namespace App\Services;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketHandler implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        var_dump($msg);
       echo "Mensaje: ".$msg."\n";
       //var_dump($msg);



       $message = json_decode($msg, true);
       //var_dump($message);
       foreach ($this->clients as $client) {
            $client->send(json_encode($message));
        }

        //Tomar Id del registro
        //    if (isset($message['record_id'])) {
        //        $recordId = $message['record_id'];

        //        foreach ($this->clients as $client) {
        //            $client->send(json_encode($message));
        //        }
        //    }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection closed: {$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}
