<?php

namespace AstralPHP\game\net\handlers\scene;

use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;

class OnSceneEntityMove {
    public function __construct(private PlayerSession $session) {}
    
    public function handle(ConnectionInterface $socket, \SceneEntityMoveCsReq $request): void {
        // foreach ($request->getEntityMotionList() as $entityMotion) {
        //     if ($motion = $entityMotion->getMotion()) {
        //         Logger::log_packet(sprintf(
        //             "[POSITION] entity_id: %d, motion: %s",
        //             $entityMotion->getEntityId(),
        //             json_encode($motion)
        //         ));
        //     }
        // }

        $rsp = new \SceneEntityMoveScRsp();
        $rsp->setRetcode(0);
        $rsp->setEntityMotionList($request->getEntityMotionList());
        
        $this->session->sendPacket($socket, $rsp);
    }
}