<?php

require '../vendor/autoload.php';


echo "------ Creating Adapter & Configs --------\n";

$configData = [
    'connection' => [
        'host' => 'localhost',
        'port' => 5672,
        'user' => 'guest',
        'pass' => 'guest',
        'vhost' => '/'
    ],
    'queues' => [
        'worker.test' => [
            'passive' => false,
            'durable' => true,
            'exclusive' => false,
            'autoDelete' => false,
            'delivery_mode' => 2
        ]
    ],
    'consume' => [
        'Simpler' => [
            'noLocal' => false,
            'noAck' => false,
            'exclusive' => false,
            'noWait' => false
        ],
    ],
    'logger' => [
        'host' => 'localhost',
        'port' => 6379,
        'key' => 'logstash',
        'channel' => 'message-queue-php'
    ]
];

$config = new Ingresse\MessageQueuePHP\Config\AMQPConfig($configData);
$adapter = new Ingresse\MessageQueuePHP\Adapter\AMQPAdapter($config);

echo "------ Testing Publisher --------\n";

$testProducer = new Ingresse\MessageQueuePHP\Publisher\Publisher($adapter, 'worker.test');


echo "------- Testing Subscriber -------\n";

$subscriber = new Ingresse\MessageQueuePHP\Subscriber\Subscriber($adapter);
$simplerConsumer = new Ingresse\MessageQueuePHP\Subscriber\Consumer\SimplerConsumer;
$subscriber
   ->setConsumer($simplerConsumer)
   ->subscribe('worker.test')
   ->consume();
