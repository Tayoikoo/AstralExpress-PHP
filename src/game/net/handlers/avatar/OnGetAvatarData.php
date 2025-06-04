<?php

namespace AstralPHP\game\net\handlers\avatar;

use Google\Protobuf\Internal\Message;
use AstralPHP\data\ExcelManager;
use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;
use GetAvatarDataScRsp;
use Avatar;

class OnGetAvatarData
{
    public function __construct(private PlayerSession $session) {}

    public function handle(ConnectionInterface $socket, Message $request): void
    {
        $avatarListData = ExcelManager::getAvatars();

        $response = new GetAvatarDataScRsp();
        $response->setIsGetAll(true);

        $avatarList = [];
        $marchAvatarIDs = [1001, 1224];
        foreach ($avatarListData as $avatarData) {
            if ($avatarData->AvatarID >= 6000) {
                continue;
            }
            if (in_array($avatarData->AvatarID, $marchAvatarIDs)) {
                continue;
            }
        
            $avatarList[] = $this->unlockAvatar($avatarData->AvatarID);
        }
        
        $avatarList[] = $this->unlockAvatar(8001);
        $avatarList[] = $this->unlockAvatar(1001);

        $response->setAvatarList($avatarList);
        $response->setCurAvatarPath(
            [
                1001 => (int)\MultiPathAvatarType::Mar_7thRogueType,
                8001 => (int)\MultiPathAvatarType::BoyMemoryType,
            ]            
        );  
        $response->setRetcode(0);

        $this->session->sendPacket($socket, $response);
    }

    private function unlockAvatar(int $avatarId): Avatar
    {
        $avatar = new Avatar();
    
        $avatar->setBaseAvatarId($avatarId);
        $avatar->setPromotion(6);
        $avatar->setRank(6);
        $avatar->setExp(0);
        $avatar->setLevel(80);
        $avatar->setHasTakenPromotionRewardList(range(0, 6));
    
        return $avatar;
    }
    
}
