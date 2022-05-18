<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

class Recv
{

    private static $Recv = NULL;

    protected $connection;

    protected $channel;

    private function __construct()
    {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
        $this->Recv();
    }

    public static function getInatance()
    {
        if (!(self::$Recv instanceof Recv)) {
            return self::$Recv = new self();
        }
        return self::$Recv;
    }

    private function __clone()
    {
    }

    public function Recv()
    {
        $this->channel->queue_declare('small_train_queue', false, false, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";


        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };

        $this->channel->basic_consume('small_train_queue', '', false, true, false, false, $callback);

        while ($this->channel->is_open()) {
            $this->channel->wait();
        }
    }
}
