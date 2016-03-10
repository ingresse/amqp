<?php

namespace Ingresse\MessageQueuePHP\Publisher;

use Ingresse\MessageQueuePHP\Publisher\PublisherInterface;
use Ingresse\MessageQueuePHP\Adapter\AdapterInterface;
use Ingresse\MessageQueuePHP\Message\Message;


class Publisher implements PublisherInterface
{
    /**
     * @var [AdapterInterface]
     */
    protected $adapter;

    /**
     * @var [string]
     */
    protected $payload;

    /**
     * @var [string]
     */
    protected $queue;

    /**
     * @var [string]
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
     * @param [string] $message
     */
    public function setMessage($message)
    {
        $this->payload = new Message($message);
        return $this;
    }

    /**
     * @return [void]
     */
    public function send()
    {
        $this->adapter
            ->send($this->payload, $this->queue, $this->exchange);
    }
}