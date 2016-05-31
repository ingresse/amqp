<?php

namespace MessageQueuePHP\Subscriber\Consumer;

use MessageQueuePHP\Subscriber\Consumer\ConsumerInterface;

class SimplerConsumer implements ConsumerInterface
{
    /**
     * @return [string]
     */
    public function getTag()
    {
        return 'Simpler';
    }

    /**
     * @param  [string] $message
     * @return [dump]
     */
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
