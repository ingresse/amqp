<?php

namespace MessageQueuePHP\Adapter;

use MessageQueuePHP\Config\Config;
use MessageQueuePHP\Adapter\AMQPAdapter;

class FactoryAdapter
{
    public function __construct(array $connection, array $logger)
    {
        $params = array_merge('connection' => $connection, 'logger' => $logger);
        return new AMQPAdapter(new Config($params));
    }
}
