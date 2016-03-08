<?php

namespace PubSub\Publisher;

interface PublisherInterface
{
    public function setMessages($messages = array());

    public function send();

}