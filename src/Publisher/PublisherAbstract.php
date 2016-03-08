<?php

namespace PubSub\Publisher;

use PubSub\Publisher\PublisherInterface;
use PubSub\Adapter\AdapterInterface;
use PubSub\Message\Message;

abstract class PublisherAbstract implements PublisherInterface
{
    protected $adapter;

    protected $payload = [];

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function setMessages($messages = array())
    {
        foreach ($messages as $payload) {
            $this->payload[] = new Message(static::CHANNEL, $payload);
        }
        return $this;
    }

    public function send()
    {
        return $this;
    }
}