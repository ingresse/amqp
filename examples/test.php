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
        'host' => 'localhost2',
        'port' => 6379,
        'key' => 'logstash',
        'channel' => 'message-queue-php',
        'path' => '/var/log/message-queue-php.log'
    ]
];

$config = new MessageQueuePHP\Config\AMQPConfig($configData);

$amqpAdapter = new MessageQueuePHP\Adapter\AMQPAdapter($config);
$loggerAdapter = new MessageQueuePHP\Logger\QueueLogger($config);

echo "------ Testing Publisher --------\n";

$testProducer = new MessageQueuePHP\Publisher\Publisher($amqpAdapter, 'worker.test');

$testProducer->setMessage('Hello World')->send();


echo "------- Testing Subscriber -------\n";

$subscriber = new MessageQueuePHP\Subscriber\Subscriber($adapter);
$simplerConsumer = new MessageQueuePHP\Subscriber\Consumer\SimplerConsumer;
$subscriber
   ->setConsumer($simplerConsumer)
   ->subscribe('worker.test')
   ->consume();
