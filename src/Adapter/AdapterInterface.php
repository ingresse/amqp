<?php

namespace Ingresse\MessageQueuePHP\Adapter;

use Ingresse\MessageQueuePHP\Message\Message;

interface AdapterInterface
{
    /**
     * @param  Message $message
     * @param  [string]  $queue
     * @param  [string]  $exchange
     * @return [void]
     */
    public function send(Message $message, $queue, $exchange);

    /**
     * @param  [string] $queue
     * @param  [string] $consumeTag
     * @param  [object] $callBack
     * @return [void]
     */
    public function consume($queue, $consumeTag, $callBack);
}