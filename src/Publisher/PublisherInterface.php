<?php

namespace Ingresse\MessageQueuePHP\Publisher;

interface PublisherInterface
{
    /**
     * @param [string] $message 
     * @return void
     */
    public function setMessage($message);

    /**
     * @return void
     */
    public function send();

}