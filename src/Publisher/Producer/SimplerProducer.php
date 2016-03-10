<?php

namespace PubSub\Publisher\Producer;

use PubSub\Publisher\PublisherAbstract;
use PubSub\Message\Message;

class SimplerProducer extends PublisherAbstract
{
    const CHANNEL = 'antifraud';

    public function send()
    {
        $this->adapter
            ->send($this->payload);
    }   
}