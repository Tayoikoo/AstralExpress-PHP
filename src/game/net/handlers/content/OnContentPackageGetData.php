<?php

namespace AstralPHP\game\net\handlers\content;

use Google\Protobuf\Internal\Message;
use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;

class OnContentPackageGetData
{
    public function __construct(private PlayerSession $session) {}

    public function handle(ConnectionInterface $socket, Message $request): void
    {
        $rsp = new \ContentPackageGetDataScRsp();
        $rsp->setRetcode(0);

        $this->session->sendPacket($socket, $rsp);
    }
}
