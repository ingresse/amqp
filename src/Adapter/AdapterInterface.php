<?php

namespace PubSub\Adapter;

interface AdapterInterface
{
    public function send($payload, $properties);
}