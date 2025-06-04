<?php

namespace AstralPHP\game\net\handlers\auth;
use AstralPHP\utils\timestamp;

use Google\Protobuf\Internal\Message;
use AstralPHP\game\net\cmd_id;
use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;
use PlayerBasicInfo;
use PlayerLoginScRsp;
class OnPlayerLogin
{
    public function __construct(private PlayerSession $session) {}

    public function handle(ConnectionInterface $socket, Message $request): void
    {
        $basicInfo = new PlayerBasicInfo();
        $basicInfo->setNickname("AstralPHP");
        $basicInfo->setLevel(70);
        $basicInfo->setExp(0);
        $basicInfo->setStamina(300);
        $basicInfo->setMcoin(1);
        $basicInfo->setHcoin(1);
        $basicInfo->setScoin(1);
        $basicInfo->setWorldLevel(6);

        $response = new PlayerLoginScRsp();
        $response->setBasicInfo($basicInfo);
        $response->setServerTimestampMs(timestamp::cur_timestamp_ms());
        $response->setRetcode(0);
        $response->setStamina(300);

        // Send the response packet using PlayerSession's method
        $this->session->sendPacket($socket, $response);
    }
}
