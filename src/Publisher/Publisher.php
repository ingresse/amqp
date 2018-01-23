<?php

namespace MessageQueuePHP\Publisher;

use MessageQueuePHP\Publisher\PublisherInterface;
use MessageQueuePHP\Adapter\AdapterInterface;
use MessageQueuePHP\Message\Message;


class Publisher implements PublisherInterface
{
    /**
     * @var MessageQueuePHP\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var MessageQueuePHP\Message\Message
     */
    protected $payload;

    /**
     * @var string
     */
    protected $queue;

    /**
     * @var string
     */
    protected $exchange;

    /**
     * @param AdapterInterface $adapter
     * @param string           $queue
     * @param string           $exchange
     */
    public function __construct(AdapterInterface $adapter, $queue = '', $exchange = '')
    {
        $this->adapter  = $adapter;
        $this->queue    = $queue;
        $this->exchange = $exchange;
    }

    /**
     * @param string $message
     * @param array  $properties
     */
    public function setMessage($message, $properties = [])
    {
        $this->payload = new Message($message, $properties);
        return $this;
    }

    /**
     * @return void
     */
    public function send()
    {
        $this->adapter
            ->send($this->payload, $this->queue, $this->exchange);
    }
}
