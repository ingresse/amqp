<?php

namespace MessageQueuePHP\Rpc;

use MessageQueuePHP\Adapter\AdapterInterface;
use MessageQueuePHP\Message\Message;

class Factory
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param string $replyTo
     * @param string $correlationId
     * @param int    $timeout
     * @return Promise
     */
    public function createPromise($replyTo, $correlationId, $consumerTag, $timeout)
    {
        $getResponse = function (Promise $promise, $promiseTimeout) use ($timeout, $consumerTag, $correlationId, $replyTo) {
            $runTimeout = $promiseTimeout ?: $timeout;
            $endTime = time() + ((int) ($runTimeout / 1000));

            do {
                if ($message = $this->adapter->receive($replyTo, $consumerTag, $runTimeout)) {
                    $channel = $message->delivery_info['channel'];
                    if ($message->has('correlation_id')
                        && $message->get('correlation_id') == $correlationId
                    ) {
                        $channel->basic_ack(
                            $message->delivery_info['delivery_tag']
                        );

                        return new Message($message->getBody(), $message->get_properties());
                    }

                    $channel->basic_reject(
                        $message->delivery_info['delivery_tag'],
                        true
                    );
                }
            } while (time() < $endTime);

            throw new TimeoutException($runTimeout, $correlationId);
        };

        return new Promise($getResponse);
    }
}
