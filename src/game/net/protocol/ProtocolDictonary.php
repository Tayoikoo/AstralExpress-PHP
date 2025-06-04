<?php

namespace AstralPHP\game\net\protocol;

use AstralPHP\common\Logger;
use AstralPHP\FolderConstants;

class ProtocolDictonary
{
    private static array $dummyCommands = [
        "GetLevelRewardTakenList",
        "GetRogueScoreRewardInfo",
        "GetGachaInfo",
        "QueryProductInfo",
        "GetQuestData",
        "GetQuestRecord",
        "GetFriendApplyListInfo",
        "GetCurAssist",
        "GetRogueHandbookData",
        "GetDailyActiveInfo",
        "GetFightActivityData",
        "GetMultipleDropInfo",
        "GetPlayerReturnMultiDropInfo",
        "GetShareData",
        "GetTreasureDungeonActivityData",
        "PlayerReturnInfoQuery",
        "GetPlayerBoardData",
        "GetActivityScheduleConfig",
        "GetMissionData",
        "GetMissionEventData",
        "GetChallenge",
        "GetCurChallenge",
        "GetRogueInfo",
        "GetExpeditionData",
        "GetJukeboxData",
        "SyncClientResVersion",
        "DailyFirstMeetPam",
        "GetMuseumInfo",
        "GetLoginActivity",
        "GetRaidInfo",
        "GetTrialActivityData",
        "GetBoxingClubInfo",
        "GetNpcStatus",
        "TextJoinQuery",
        "GetSpringRecoverData",
        "GetChatFriendHistory",
        "GetSecretKeyInfo",
        "GetVideoVersionKey",
        "GetPhoneData",
        "GetMarkItemList",
        "GetAllServerPrefsData",
        "GetRogueCommonDialogueData",
        "GetRogueEndlessActivityData",
        "GetMainMissionCustomValue",
        "GetAssistHistory",
        "RogueTournQuery",
        "GetBattleCollegeData",
        "GetHeartDialInfo",
        "HeliobusActivityData",
        "GetEnteredScene",
        "GetAetherDivideInfo",
        "GetMapRotationData",
        "GetRogueCollection",
        "GetRogueExhibition",
        "GetNpcMessageGroup",
        "GetFriendLoginInfo",
        "GetChessRogueNousStoryInfo",
        "CommonRogueQuery",
        "GetStarFightData",
        "EvolveBuildQueryInfo",
        "GetAlleyInfo",
        "GetAetherDivideChallengeInfo",
        "GetStrongChallengeActivityData",
        "GetOfferingInfo",
        "ClockParkGetInfo",
        "GetGunPlayData",
        "SpaceZooData",
        "GetUnlockTeleport",
        "TravelBrochureGetData",
        "RaidCollectionData",
        "GetChatEmojiList",
        "GetTelevisionActivityData",
        "GetTrainVisitorRegister",
        "GetLoginChatInfo",
        "GetFeverTimeActivityData",
        "GetAllSaveRaid",
        "GetPlayerDetailInfo",
        "GetFriendBattleRecordDetail",
        "GetFriendDevelopmentInfo",
        "FinishTalkMission",
        "RogueTournGetPermanentTalentInfo",
        "ChessRogueQuery",
        "GetTrackPhotoActivityData",
        "GetSwordTrainingData",
        "GetSummonActivityData",
        "MatchThreeGetData",
        "GetDrinkMakerData",
        "UpdateServerPrefsData",
        "GetShopList",
        "UpdateTrackMainMissionId",
        "RelicRecommend",
        "EnterSection",
        "RogueArcadeGetInfo",
        "GetPetData",
        "GetFightFestData",
        "DifficultyAdjustmentGetData",
        "GetMail",
        "GetRechargeGiftInfo",
        "GetPreAvatarGrowthInfo",
        "GetPreAvatarActivityList"
    ];
    
    private static array $idToNameProtocol = [];
    private static array $nameToIdProtocol = [];
    private static array $cmdMap = [];
    public static function init(): void
    {
        $jsonPath = FolderConstants::DATA_FOLDER . "packetIds.json";

        if (!file_exists($jsonPath)) {
            throw new \RuntimeException("packetIds.json not found at: $jsonPath");
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        if (!is_array($data)) {
            throw new \RuntimeException("Invalid JSON structure in packetIds.json");
        }

        self::$idToNameProtocol = $data;
        self::$nameToIdProtocol = array_flip($data);
    }

    /**
     * Get protocol name from ID.
     *
     * @param int $id
     * @return string
     */
    public static function getProtocolNameFromId(int $id): string
    {
        return self::$idToNameProtocol[$id] ?? "unknown";
    }

    /**
     * Get protocol ID from name.
     *
     * @param string $name
     * @return int
     */
    public static function getProtocolIdFromName(string $name): int
    {
        return self::$nameToIdProtocol[$name] ?? -1;
    }

    public static function getAll(): array
    {
        return self::$idToNameProtocol;
    }

    public static function getCmdIdByClass(string $className): ?int {
        $baseClassName = basename(str_replace('\\', '/', $className));    
        foreach (self::$nameToIdProtocol as $name => $cmdId) {
            if ($name === $baseClassName) {
                return (int)$cmdId;
            }
        }
        Logger::log_gameserver("No match found for class: '{$baseClassName}'");
        return null;
    }
    
    public static function isDummyPacket(string $packetName): bool
    {
        return in_array($packetName, self::$dummyCommands);
    }
        
}
