<?php

namespace MessageQueuePHP\Logger;

use Monolog\Logger;
use Monolog\Handler\RedisHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LogstashFormatter;
use MessageQueuePHP\Message\Message;
use MessageQueuePHP\Adapter\AdapterInterface;
use MessageQueuePHP\Config\ConfigInterface;
use Predis\Client as RedisClient;
use Exception;

class QueueLogger implements AdapterInterface
{
    /**
     * @var [Monolog\Logger]
     */
    private $logger;

    /**
     * @param string $channel
     * @param string $key
     * @param string $host
     * @param string $port
     */
    public function __construct(ConfigInterface $config)
    {
        $configLogger = $config->getConfig();

        $host    = $configLogger['logger']['host'];
        $port    = $configLogger['logger']['port'];
        $key     = $configLogger['logger']['key'];
        $channel = $configLogger['logger']['channel'];
        $path    = $configLogger['logger']['path'];

        $redisClient  = new RedisClient([
            'scheme' => 'tcp',
            'host'   => $host,
            'port'   => $port
        ]);

        try {
            $redisClient->ping();
            $handler   = new RedisHandler($redisClient, $key);
        } catch (Exception $e) {
            $handler = new StreamHandler($path);
        }

        $formatter = new LogstashFormatter($channel);
        $handler->setFormatter($formatter);

        $this->logger = new Logger($channel, array($handler));
    }

    /**
     * @param string $message
     * @param string $level
     */
    public function setMessage($message, $level, $extras = array())
    {
        $this->logger->$level($message, $extras);
    }

    /**
     * @param  Message $message
     * @param  string $queue
     * @param  string $exchange
     * @return void
     */
    public function send(Message $message, $queue, $exchange)
    {
        $this->setMessage(
            'Message Queue is not Working', 
            'warning', 
            [
                'message'  => $message->getPayload(), 
                'queue'    => $queue, 
                'exchange' => $exchange
            ]
        );
    }

    /**
     * @param  string $queue
     * @param  string $consumeTag
     * @param  string $callback
     */
    public function consume($queue, $consumeTag, $callback)
    {
        /**
         * Not Implemented for this adapter
         */
    }
}
