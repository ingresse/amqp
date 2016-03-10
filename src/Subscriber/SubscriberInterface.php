<?php

namespace Ingresse\MessageQueuePHP\Subscriber;

use Ingresse\MessageQueuePHP\Subscriber\Consumer\ConsumerInterface;

interface SubscriberInterface
{
    /**
     * @param ConsumerInterface $consumer
     */
    public function setConsumer(ConsumerInterface $consumer);

    /**
     * @param  [string] $queue
     */
    public function subscribe($queue);

    /**
     * @return [void]
     */
    public function consume();
}