<?php

namespace MessageQueuePHP\Adapter;

use MessageQueuePHP\Config\AMQPConfig as Config;
use MessageQueuePHP\Adapter\AMQPAdapter;

class FactoryAdapter
{
    /**
     * @param  array  $connection
     * @param  array  $queues
     * @param  array  $logger
     * @return MessageQueuePHP\Adapter\AMQPAdapter
     */
    public function create(array $connection, array $queues, array $logger)
    {
        $params = [
            'connection' => $connection,
            'queues'     => $queues,
            'logger'     => $logger
        ];
        return new AMQPAdapter(new Config($params));
    }
}
