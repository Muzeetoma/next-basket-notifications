<?php

namespace App\Core;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

class RabbitMQService
{
    protected $connection;
    protected $channel;
    protected $exchange = 'amq.direct';
    protected $queue = 'user_queue';
    protected $routingKey = '';

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_LOGIN'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );

        $this->channel = $this->connection->channel();

        $this->channel->exchange_declare($this->exchange, 'direct', false, true, false);
        $this->channel->queue_declare($this->queue, false, true, false, false);
        $this->channel->queue_bind($this->queue, $this->exchange, $this->routingKey);
    }

    public function publish($message)
    {
        try{
            $msg = new AMQPMessage($message);
            $this->channel->basic_publish($msg, $this->exchange, $this->routingKey);
        } catch (\Exception $e) {
            Log::error('Error publishing messages to RabbitMQ: ' . $e->getMessage());
        }
    }

    public function consume($callback)
    {

        try{
            $this->channel->basic_consume($this->queue, '', false, true, false, false, $callback);

            while ($this->channel->is_consuming()) {
                $this->channel->wait();
            }
        } catch (\Exception $e) {
            Log::error('Error consuming messages from RabbitMQ: ' . $e->getMessage());
        }
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}