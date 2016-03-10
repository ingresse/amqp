<?php

namespace PubSub\Subscriber;

use PubSub\Subscriber\SubscriberInterface;
use PubSub\Subscriber\Consumer\ConsumerInterface;
use PubSub\Adapter\AdapterInterface;

class Subscriber implements SubscriberInterface
{
    private $adapter;

    private $consumer;

    private $queue;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function setConsumer(ConsumerInterface $consumer)
    {
        $this->consumer = $consumer;
        return $this;
    }

    public function subscribe($queue)
    {
        $this->queue = $queue;
        return $this;
    }

    public function consume()
    {
        $this
            ->adapter
            ->consume(
                $this->queue, 
                $this->consumer->getTag(),
                array($this->consumer, 'work')
            );
    }
}