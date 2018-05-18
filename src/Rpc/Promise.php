<?php

namespace MessageQueuePHP\Rpc;

use MessageQueuePHP\Message\Message;
use Closure;
use Exception;

class Promise
{
    /**
     * @var Closure
     */
    private $callback;

    /**
     * @var Message
     */
    private $message;

    /**
     * @param Closure $callback
     */
    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param int $timeout
     * @return Message
     */
    public function getResponse($timeout = null)
    {
        if (null == $this->message) {
            if ($message = $this->receive($this->callback, $this, $timeout)) {
                $this->message = $message;
            }
        }

        return $this->message;
    }

    /**
     * @param Closure $callback
     * @param array   $args
     * @return Message
     */
    private function receive(Closure $callback, ...$args)
    {
        $message = call_user_func_array($callback, $args);

        if (!empty($message) && !$message instanceof Message) {
            throw new Exception(sprintf(
                'Expected "%s" but got: "%s"', Message::class, is_object($message) ? get_class($message) : gettype($message)));
        }

        return $message;
    }
}
