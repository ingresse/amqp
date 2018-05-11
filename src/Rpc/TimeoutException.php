<?php

namespace MessageQueuePHP\Rpc;

class TimeoutException extends \RuntimeException
{

    /**
     * @param int    $timeout
     * @param string $correlationId
     */
    function __construct($timeout, $correlationId)
    {
        parent::__construct(sprintf(
            'Rpc call timeout is reached without receiving a reply message. Timeout: %s, CorrelationId: %s',
            $timeout, $correlationId
        ));
    }
}
