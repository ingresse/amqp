<?php

namespace MessageQueuePHP\Adapter;

use MessageQueuePHP\Message\Message;

interface AdapterInterface
{
    /**
     * @param  MessageQueuePHP\Message\Message $message
     * @param  string                          $queue
     * @param  string                          $exchange
     * @return void
     */
    public function send(Message $message, $queue, $exchange);

    /**
     * @param  string        $queue
     * @param  string        $consumeTag
     * @param  callable|null $callBack
     * @return void
     */
    public function consume($queue, $consumeTag, $callBack);
}
