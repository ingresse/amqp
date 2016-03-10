<?php

namespace Ingresse\MessageQueuePHP\Subscriber;

use Ingresse\MessageQueuePHP\Subscriber\SubscriberInterface;
use Ingresse\MessageQueuePHP\Subscriber\Consumer\ConsumerInterface;
use Ingresse\MessageQueuePHP\Adapter\AdapterInterface;

class Subscriber implements SubscriberInterface
{
    /**
     * @var [AdapterInterface]
     */
    private $adapter;

    /**
     * @var [ConsumerInterface]
     */
    private $consumer;

    /**
     * @var [string]
     */
    private $queue;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param ConsumerInterface $consumer
     */
    public function setConsumer(ConsumerInterface $consumer)
    {
        $this->consumer = $consumer;
        return $this;
    }

    /**
     * @param  [string] $queue
     * @return [void]
     */
    public function subscribe($queue)
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * @return [void]
     */
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