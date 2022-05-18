<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class Mq
{

    private static $Mq = NULL;

    protected $connection;

    protected $channel;

    private function __construct()
    {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
        $this->Send();
    }

    public static function getInstance()
    {
        if (!(self::$Mq instanceof self)) {
            return self::$Mq = new self();
        }
        return self::$Mq;
    }

    private function __clone()
    {
    }

    private function Send()
    {
        $this->channel->queue_declare('small_train_queue', false, false, false, false);

        $msg = new AMQPMessage('du du du du du du du du  sanyuanya !');
        $this->channel->basic_publish($msg, '', 'small_train_queue');

        echo " [x] Sent 'Hello World!'\n";
    }
}

Mq::getInstance();
