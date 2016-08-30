<?php

namespace MessageQueuePHP\Adapter;

use MessageQueuePHP\Config\AMQPConfig as Config;
use MessageQueuePHP\Adapter\AMQPAdapter;

class FactoryAdapter
{
    public function create(array $connection, array $logger)
    {
        $params = ['connection' => $connection, 'logger' => $logger];
        return new AMQPAdapter(new Config($params));
    }
}
