<?php

namespace Ingresse\MessageQueuePHP\Adapter;

use Ingresse\MessageQueuePHP\Adapter\AdapterInterface;
use Ingresse\MessageQueuePHP\Config\ConfigInterface;
use Ingresse\MessageQueuePHP\Message\Message;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPAdapter implements AdapterInterface
{
    /**
     * @var [ConfigInterface]
     */
    private $config;

    /**
     * @var [AMQPStreamConnection]
     */
    private $connection;

    /**
     * @var [type]
     */
    private $channel;

    /**
     * @var [string]
     */
    private $exchange;

    /**
     * @var array
     */
    private $queues = [];

    /**
     * @param ConfigInterface $config
     */
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

    /**
     * @return [void]
     */
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

    /**
     * @param [string] $exchange
     * @param [string] $type
     */
    public function setExchange($exchange, $type) 
    {
        $this->exchange = $exchange;
        $this->channel->exchange_declare(
            $exchange, 
            $type
        );
    }

    /**
     * @return [string]
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @return [array]
     */
    public function getQueues()
    {
        return $this->queues;
    }

    /**
     * @param  [string] $queue
     * @param  [string] $exchange
     * @return [$this]
     */
    public function bind($queue, $exchange)
    {
        $this->channel->queue_bind($queue, $exchange);
        return $this;
    }

    /**
     * @param  Message $message
     * @param  [string]  $queue
     * @param  [string]  $exchange
     * @return [void]
     */
    public function send(Message $message, $queue, $exchange)
    {
        $amqpMessage = new AMQPMessage($message->getPayload());

        $this->defineDeliveryMode($amqpMessage, $queue);

        $this->channel->basic_publish(
            $amqpMessage, 
            $exchange, 
            $queue
        );
    }

    /**
     * @param  [string] $queue
     * @param  [string] $consumeTag
     * @param  [object] $callBack
     * @return [void]
     */
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

    /**
     * @return [void]
     */
    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * @param  [Message] &$message
     * @param  [string] $queueName
     * @return [void]
     */
    private function defineDeliveryMode(&$message, $queueName)
    {
        $params = $this->config['queues'][$queueName];
        $message->set('delivery_mode', $params['delivery_mode']);
    }
}
