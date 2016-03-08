<?php

namespace PubSub\Subscriber\Consumer;

use PubSub\Subscriber\Consumer\ConsumerInterface;

class SimplerConsumer implements ConsumerInterface
{
    public function getTag()
    {
        return static::class;
    }

    public function work($message)
    {
        $message
            ->delivery_info['channel']
            ->basic_ack(
                $message
                    ->delivery_info['delivery_tag']
                );
        var_dump($message->body);
    }
}