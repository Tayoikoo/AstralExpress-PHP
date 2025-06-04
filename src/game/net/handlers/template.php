<?php

namespace AstralPHP\game\net\handlers;

use Google\Protobuf\Internal\Message;
use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;

class template
{
    public function __construct(private PlayerSession $session) {}

    public function handle(ConnectionInterface $socket, Message $request): void
    {

    }
}
