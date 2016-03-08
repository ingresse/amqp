<?php

namespace PubSub\Subscriber\Consumer;

interface ConsumerInterface
{
    public function getTag();

    public function work($message);
}