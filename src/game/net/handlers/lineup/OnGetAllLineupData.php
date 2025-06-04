<?php

namespace AstralPHP\game\net\handlers\lineup;

use Google\Protobuf\Internal\Message;
use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;

use GetAllLineupDataScRsp;

class OnGetAllLineupData
{
    public function __construct(private PlayerSession $session) {}

    public function handle(ConnectionInterface $socket, Message $request): void
    {
        $rsp = new GetAllLineupDataScRsp();
   
        $avatarIds = [1308];
    
        $avatars = [];
        foreach ($avatarIds as $i => $id) {
            $avatar = new \LineupAvatar();
            $avatar->setId($id);
            $avatar->setHp(10000);
            $avatar->setSlot($i);
            $avatar->setSatiety(0);
    
            $sp = new \SpBarInfo();
            $sp->setCurSp(10000);
            $sp->setMaxSp(10000);
            $avatar->setSpBar($sp);
    
            $avatar->setAvatarType((int)\AvatarType::AVATAR_FORMAL_TYPE);
            $avatars[] = $avatar;
        }

        $lineupInfo = new \LineupInfo();
        $lineupInfo->setName("PHP-SR Team");
        $lineupInfo->setPlaneId(20101);
        $lineupInfo->setAvatarList($avatars);
        $lineupInfo->setMP(5); 
        $lineupInfo->setMaxMp(5);         

        $rsp->setLineupList([$lineupInfo]);
        $rsp->setRetcode(0);
        $rsp->setCurIndex(0);

        $this->session->sendPacket($socket, $rsp);
    }
}
