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
     * @param MessageQueuePHP\Config\ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        #extract $host, $port, $key, $channel and $path
        extract($this->prepareConfig($config->getConfig()));

        $redisClient  = new RedisClient([
            'scheme' => 'tcp',
            'host'   => $host,
            'port'   => $port
        ]);

        try {
            $redisClient->ping();
            $handler = new RedisHandler($redisClient, $key);
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
     * @param array  $extras
     */
    public function setMessage($message, $level, array $extras = array())
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

    /**
     * @param  array $config
     * @throws Exception
     * @return array
     */
    private function prepareConfig($config)
    {
        if (!isset($config['logger'])) {
            throw new Exception('LoggerAdapter can not be loaded.
                Check config settings.');
        }

        $configs = [
            'host' => 'localhost',
            'port' => 6379,
            'key'  => 'logstash',
            'channel' => 'message-queue-php',
            'path' => '/var/log/message-queue-php.log'
        ];

        if (isset($config['logger']['host'])) {
            $configs['host'] = $config['logger']['host'];
        }

        if (isset($config['logger']['port'])) {
            $configs['port'] = $config['logger']['port'];
        }

        if (isset($config['logger']['key'])) {
            $configs['key'] = $config['logger']['key'];
        }

        if (isset($config['logger']['channel'])) {
            $configs['channel'] = $config['logger']['channel'];
        }

        if (isset($config['logger']['path'])) {
            $configs['path'] = $config['logger']['path'];
        }

        return $configs;
    }
}
