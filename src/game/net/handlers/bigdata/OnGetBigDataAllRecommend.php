<?php

namespace AstralPHP\game\net\handlers\bigdata;

use Google\Protobuf\Internal\Message;
use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;

use GetBigDataAllRecommendScRsp;

class OnGetBigDataAllRecommend
{
    public function __construct(private PlayerSession $session) {}

    public function handle(ConnectionInterface $socket, \GetBigDataAllRecommendCsReq $request): void
    {
        $rsp = new GetBigDataAllRecommendScRsp();

        $rsp->setBigDataRecommendType($request->getBigDataRecommendType());
        $this->session->sendPacket($socket, $rsp);
    }
}
