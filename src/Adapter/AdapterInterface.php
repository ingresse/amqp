<?php

namespace PubSub\Adapter;

interface AdapterInterface
{
    public function send($payload);

    public function consume($queue, $consumeTag, $callBack);
}