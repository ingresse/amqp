<?php

namespace PubSub\Adapter;

use PubSub\Adapter\AdapterInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPAdapter implements AdapterInterface
{
    private $connection;

    private $channel;

    private $exchange;

    private $queues = [];

    public function __construct(
        $host, 
        $port, 
        $user, 
        $pass, 
        $vhost
    ) {
        $this->connection = new AMQPStreamConnection(
            $host, 
            $port, 
            $user, 
            $pass, 
            $vhost
        );
        $this->channel = $this->connection->channel();
    }

    public function setQueue(
        $queue, 
        $passive = false, 
        $durable = true, 
        $exclusive = false,
        $autoDelete = false,
        $noWait = false
    ) {
        $this->channel->queue_declare(
            $queue, 
            $passive, 
            $durable,
            $exclusive,
            $autoDelete, 
            $noWait 
        );
        return $this;
    }

    public function setExchange(
        $exchange, 
        $type, 
        $passive = false, 
        $durable = false, 
        $autoDelete = false
    ) {
        $this->exchange = $exchange;
        $this->channel->exchange_declare(
            $exchange, 
            $type, 
            $passive, 
            $durable, 
            $autoDelete
        );
        return $this;
    }

    public function getExchange()
    {
        return $this->exchange;
    }

    public function getQueues()
    {
        return $this->queues;
    }

    public function bind($queue, $exchange)
    {
        $this->channel->queue_bind($queue, $exchange);
        return $this;
    }

    public function send($payload, $properties)
    {
        foreach($payload as $item) {
            $message = new AMQPMessage($item->getPayload(), $properties);
            $this->channel->basic_publish(
                $message, 
                $this->getExchange(), 
                $item->getLabel()
            );
        }
    }

    public function consume(
        $queue,
        $consumeTag,
        $noLocal,
        $noAck,
        $exclusive,
        $noWait,
        $callBack
    ) {
        $this->channel->basic_consume(
            $queue,
            $consumeTag,
            $noLocal,
            $noAck,
            $exclusive,
            $noWait,
            $callBack
        );
        
        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->close();
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
}