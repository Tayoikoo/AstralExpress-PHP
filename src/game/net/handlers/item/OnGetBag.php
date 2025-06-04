<?php

namespace AstralPHP\game\net\handlers\item;

use Google\Protobuf\Internal\Message;
use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;

class OnGetBag
{
    public function __construct(private PlayerSession $session) {}

    public function handle(ConnectionInterface $socket, Message $request): void
    {
        $rsp = new \GetBagScRsp();
        
        $rsp->setRetcode(0);

        $this->session->sendPacket($socket, $rsp);
    }
}
