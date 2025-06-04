<?php

namespace AstralPHP\game\net\handlers\scene;

use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;

class OnGetSceneMapInfo
{
    public function __construct(private PlayerSession $session) {}

    public function handle(ConnectionInterface $socket, \GetSceneMapInfoCsReq $request): void
    {
        $mapInfos = [];

        foreach ($request->getEntryIdList() as $entryId) {
            $mapInfo = new \SceneMapInfo();
            $mapInfo->setEntryId($entryId);

            $mapInfos[] = $mapInfo;
        }

        // Create the response message
        $response = new \GetSceneMapInfoScRsp();
        $response->setSceneMapInfo($mapInfos);
        $response->setRetcode(0);

        $this->session->sendPacket($socket, $response);
    }
}
