##Message Queue php library to publish and subscribe to queues with diferent types of adapters.

##Current supported adapters:

- [RabbitMQ - AMQP 0.9.1](https://www.rabbitmq.com/tutorials/amqp-concepts.html)

##Installation

With Composer
Add ingresse/message-queue-php as dependency in composer.json

```javascript
    "require": {
        ...
        "ingresse/message-queue-php" : "1.*"
        ...
    }
```

Run composer update.

Now the message-queue-php will be autoloaded into your project.

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
            path' => '/var/logs/message-queue-php.log'
        ]
    ];

    $config = new MessageQueuePHP\Config\AMQPConfig($configData);
    $amqpAdapter = new MessageQueuePHP\Adapter\AMQPAdapter($config);


    $myPublisher = new MessageQueuePHP\Publisher\Publisher($amqpAdapter, 'worker.test');
    $myPublisher
            ->setMessage($myData)
            ->send();

    $subscriber = new MessageQueuePHP\Subscriber\Subscriber($amqpAdapter);
    $simplerConsumer = new MessageQueuePHP\Subscriber\Consumer\SimplerConsumer;
    $subscriber
       ->setConsumer($simplerConsumer)
       ->subscribe('worker.test')
       ->consume();
```