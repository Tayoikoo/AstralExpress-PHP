<?php

namespace AstralPHP\game\net\handlers\bigdata;

use GetBigDataRecommendScRsp;
use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;

class OnGetBigDataRecommend
{
    public function __construct(private PlayerSession $session) {}

    public function handle(ConnectionInterface $socket, \GetBigDataRecommendCsReq $request): void
    {
        $rsp = new GetBigDataRecommendScRsp();

        $rsp->setBigDataRecommendType($request->getBigDataRecommendType());
        $rsp->setBigDataId($request->getBigDataId());
        $this->session->sendPacket($socket, $rsp);
    }
}
