<?php

namespace PubSub\Subscriber;

use PubSub\Subscriber\Consumer\ConsumerInterface;

interface SubscriberInterface
{
    public function setConsumer(ConsumerInterface $consumer);

    public function subscribe($queue);

    public function consume();
}