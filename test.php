<?php

require 'vendor/autoload.php';


echo '------ Testing Publisher ------- ';

$messages = [];

for ($i = 1; $i <= 10000; $i++){
    $data = ['id' => 467709,
             'email' => 'my@email.com',
             'domain' => 'email.com',
             'name' => 'Teste'
             ];
    $messages[] = json_encode($data);
}

$adapter = new PubSub\Adapter\AMQPAdapter('localhost', 5672, 'guest', 'guest', '/');

$simplerProducer = new PubSub\Publisher\Producer\SimplerProducer($adapter);

$simplerProducer
    ->setMessages($messages)
    ->send();

echo '------- Testing Subscriber -------';

$subscriber = new PubSub\Subscriber\Subscriber($adapter);
$simplerConsumer = new PubSub\Subscriber\Consumer\SimplerConsumer;

$subscriber
    ->setConsumer($simplerConsumer)
    ->subscribe($simplerProducer::CHANNEL)
    ->consume();
