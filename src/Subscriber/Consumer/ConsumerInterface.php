<?php

namespace MessageQueuePHP\Subscriber\Consumer;

interface ConsumerInterface
{
    /**
     * @return string
     */
    public function getTag();

    /**
     * @param string $message
     */
    public function work($message);
}
