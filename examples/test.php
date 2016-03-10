<?php

require '../vendor/autoload.php';


echo "------ Creating Adapter & Configs --------\n";

$configData = [
    'connection' => [
        'host' => '107.23.60.208',
        'port' => 5672,
        'user' => 'guest',
        'pass' => 'guest',
        'vhost' => '/'
    ],
    'queues' => [
        'supertramp' => [
            'passive' => false,
            'durable' => true,
            'exclusive' => false,
            'autoDelete' => false,
            'delivery_mode' => 2
        ],
        'antifraud' => [
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
        'Complexr' => [
            'noLocal' => false,
            'noAck' => false,
            'exclusive' => false,
            'noWait' => false
        ]
    ]
];

$config = new Ingresse\MessageQueuePHP\Config\AMQPConfig($configData);
$adapter = new Ingresse\MessageQueuePHP\Adapter\AMQPAdapter($config);


echo "------ Testing Publisher --------\n";

$simplerProducer = new Ingresse\MessageQueuePHP\Publisher\Publisher($adapter, 'antifraud');

for ($i = 1; $i <= 10000; $i++){
    $data = ['id' => 467709,
             'email' => 'my@email.com',
             'domain' => 'email.com',
             'name' => 'Teste'
             ];
    $simplerProducer
        ->setMessage(json_encode($data))
        ->send();
}


echo "------- Testing Subscriber -------\n";

$subscriber = new Ingresse\MessageQueuePHP\Subscriber\Subscriber($adapter);
$simplerConsumer = new Ingresse\MessageQueuePHP\Subscriber\Consumer\SimplerConsumer;
$subscriber
    ->setConsumer($simplerConsumer)
    ->subscribe('antifraud')
    ->consume();
