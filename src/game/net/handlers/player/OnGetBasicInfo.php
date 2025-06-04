<?php

namespace AstralPHP\game\net\handlers\player;

use Google\Protobuf\Internal\Message;
use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;

use GetBasicInfoScRsp;

class OnGetBasicInfo
{
    public function __construct(private PlayerSession $session) {}

    public function handle(ConnectionInterface $socket, Message $request): void
    {
        $rsp = new GetBasicInfoScRsp();

        $rsp->setRetcode(0);
        $rsp->setGender(1);
        $rsp->setIsGenderSet(true);

        $this->session->sendPacket($socket, $rsp);
    }
}
