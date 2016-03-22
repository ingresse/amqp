<?php

namespace MessageQueuePHP\Message;

class Message
{
    /**
     * @var [stribg]
     */
    protected $payload;

    /**
     * @param [string] $payload
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return [string]
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return [string]
     */
    public function toJson()
    {
        return json_encode($this->payload);
    }
}
