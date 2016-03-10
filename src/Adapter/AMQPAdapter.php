<?php

namespace PubSub\Adapter;

use PubSub\Adapter\AdapterInterface;
use PubSub\Config\ConfigInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPAdapter implements AdapterInterface
{
    private $config;

    private $connection;

    private $channel;

    private $exchange;

    private $queues = [];

    public function __construct(ConfigInterface $config) 
    {
        $this->config = $config->getConfig();
        $connection   = $this->config['connection'];

        $this->connection = new AMQPStreamConnection(
            $connection['host'], 
            $connection['port'],
            $connection['user'],
            $connection['pass'],
            $connection['vhost']
        );
        $this->channel = $this->connection->channel();
        $this->setQueues();
    }

    private function setQueues() 
    {
        foreach ($this->config['queues'] as $queue => $params) {
            $this->queues[] = $this->channel->queue_declare(
                                $queue, 
                                $params['passive'], 
                                $params['durable'],
                                $params['exclusive'],
                                $params['autoDelete']
                            );
        }
    }

    public function setExchange($exchange, $type) 
    {
        $this->exchange = $exchange;
        $this->channel->exchange_declare(
            $exchange, 
            $type
        );
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

    public function send($payload)
    {
        foreach($payload as $item) {
            $message = new AMQPMessage($item->getPayload());
            $this->defineDeliveryMode($message, $item->getLabel());
            $this->channel->basic_publish(
                $message, 
                $this->getExchange(), 
                $item->getLabel()
            );
        }
    }

    private function defineDeliveryMode(&$message, $channelName)
    {
        $params = $this->config['queues'][$channelName];
        $message->set('delivery_mode', $params['delivery_mode']);
    }

    public function consume($queue, $consumeTag, $callBack)
    {
        $params = $this->config['consume'][$consumeTag];
        $this->channel->basic_consume(
            $queue,
            $consumeTag,
            $params['noLocal'],
            $params['noAck'],
            $params['exclusive'],
            $params['noWait'],
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