<?php

namespace PubSub\Message;

class Message
{
    protected $label;

    protected $payload;


    public function __construct($label, $payload)
    {
        $this->label = $label;
        $this->payload = $payload;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}
