<?php

namespace PubSub\Config;

use PubSub\Config\ConfigInterface;

class AMQPConfig implements ConfigInterface
{
    private $amqpConfig = [];

    public function __construct($config)
    {
        $this->amqpConfig = $config;
    }

    public function getConfig()
    {
        return $this->amqpConfig;
    }
}