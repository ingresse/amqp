<?php

namespace Ingresse\MessageQueuePHP\Config;

use Ingresse\MessageQueuePHP\Config\ConfigInterface;

class AMQPConfig implements ConfigInterface
{
    /**
     * @var array
     */
    private $amqpConfig = [];

    /**
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->amqpConfig = $config;
    }

    /**
     * @return [array]
     */
    public function getConfig()
    {
        return $this->amqpConfig;
    }
}