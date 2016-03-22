<?php

namespace MessageQueuePHP\Logger;

use Monolog\Logger;
use Monolog\Handler\RedisHandler;
use Monolog\Formatter\LogstashFormatter;
use Predis\Client as RedisClient;

class QueueLogger
{
	private $logger;

	public function __construct($channel, $key, $host, $port)
	{
		$redisClient = new RedisClient([
                    'scheme' => 'tcp',
                    'host'   => $host,
                    'port'   => $port
                ]);

		$redisHandler = new RedisHandler($redisClient, $key);
		$formatter    = new LogstashFormatter($channel);
		$redisHandler->setFormatter($formatter);

		$this->logger = new Logger($channel, array($redisHandler));
	}

	public function setMessage($message, $level)
	{
		$this->logger->$level($message);
	}
}