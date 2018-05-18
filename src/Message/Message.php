<?php

namespace MessageQueuePHP\Message;

class Message
{
    /**
     * @var array
     */
    protected $payload;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @param array $payload
     * @param array $properties
     */
    public function __construct($payload, $properties = [])
    {
        $this->payload = $payload;
        $this->properties = $properties;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->payload);
    }
}
