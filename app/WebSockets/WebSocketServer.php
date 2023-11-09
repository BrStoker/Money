<?php


namespace App\WebSockets;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


class WebSocketServer implements MessageComponentInterface
{
    public function onOpen(ConnectionInterface $connection)
    {

    }

    public function onClose(ConnectionInterface $connection)
    {

    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {


    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        $from->send('Сервер получил сообщение: ' . $message);
    }
}
