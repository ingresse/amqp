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
            $formatter = new LogstashFormatter($channel);
            $handler->setFormatter($formatter);
        }catch (Exception $e) {
            $handler = new StreamHandler($path);
        }

        $this->logger = new Logger($channel, array($handler));
    }

    /**
     * @param string $message
     * @param string $level
     */
    public function setMessage($message, $level)
    {
        $this->logger->$level($message);
    }

    /**
     * @param  Message $message
     * @param  string $queue
     * @param  string $exchange
     * @return void
     */
    public function send(Message $message, $queue, $exchange)
    {
        $this->logger->info($message->getPayload(), [$queue]);
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