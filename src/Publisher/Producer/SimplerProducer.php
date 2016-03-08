<?php

namespace PubSub\Publisher\Producer;

use PubSub\Publisher\PublisherAbstract;
use PubSub\Message\Message;

class SimplerProducer extends PublisherAbstract
{
    const CHANNEL = 'simpler';

    public function send()
    {
        $properties = [
            'delivery_mode' => 2
        ];

        $this->adapter
            ->setQueue(self::CHANNEL)
            ->send($this->payload, $properties);
    }   
}