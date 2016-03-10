<?php

require '../vendor/autoload.php';


echo "------ Generating Messages --------\n";

$messages = [];

for ($i = 1; $i <= 100000; $i++){
    $data = ['id' => 467709,
             'email' => 'my@email.com',
             'domain' => 'email.com',
             'name' => 'Teste'
             ];
    $messages[] = json_encode($data);
}

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

$config = new PubSub\Config\AMQPConfig($configData);
$adapter = new PubSub\Adapter\AMQPAdapter($config);


echo "------ Testing Publisher --------\n";

$simplerProducer = new PubSub\Publisher\Producer\SimplerProducer($adapter);
$simplerProducer
    ->setMessages($messages)
    ->send();

echo "------- Testing Subscriber -------\n";

$subscriber = new PubSub\Subscriber\Subscriber($adapter);
$simplerConsumer = new PubSub\Subscriber\Consumer\SimplerConsumer;
$subscriber
    ->setConsumer($simplerConsumer)
    ->subscribe($simplerProducer::CHANNEL)
    ->consume();
