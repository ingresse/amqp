# Message Queue PHP

Message Queue php library to publish and subscribe to queues with diferent types of adapters.

## Current supported adapters:
	- [RabbitMQ - AMQP 0.9.1](https://www.rabbitmq.com/tutorials/amqp-concepts.html)

## Installation
### With Composer

1. Add ingresse/message-queue-php as dependency in composer.json 

```javascript
    "require": {
        ...
        "ingresse/message-queue-php" : "1.*"
        ...
    }
```

2. Run `composer update`.

3. Now the message-queue-php will be autoloaded into your project.

```php
    require 'vendor/autoload.php';

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
	        ]
	    ],
	    'logger' => [
	        'host' => 'localhost',
	        'port' => 6379,
	        'key' => 'logstash',
	        'channel' => 'message-queue-php'
	    ]
	];

    $config = new Ingresse\MessageQueuePHP\Config\AMQPConfig($configData);
	$amqpAdapter = new Ingresse\MessageQueuePHP\Adapter\AMQPAdapter($config);


	$myPublisher = new Ingresse\MessageQueuePHP\Publisher\Publisher($amqpAdapter, 'worker.test');
	$myPublisher
            ->setMessage($myData)
            ->send();

    $subscriber = new Ingresse\MessageQueuePHP\Subscriber\Subscriber($amqpAdapter);
	$simplerConsumer = new Ingresse\MessageQueuePHP\Subscriber\Consumer\SimplerConsumer;
	$subscriber
	   ->setConsumer($simplerConsumer)
	   ->subscribe('worker.test')
	   ->consume();
```
