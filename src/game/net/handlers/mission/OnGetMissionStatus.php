<?php

namespace AstralPHP\game\net\handlers\mission;

use Google\Protobuf\Internal\Message;
use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;
use AstralPHP\utils\timestamp;
use GetMissionStatusCsReq;
use GetMissionStatusScRsp;
use Mission;
use MissionStatus;

class OnGetMissionStatus
{
    public function __construct(private PlayerSession $session) {}

    public function handle(ConnectionInterface $socket, Message $request): void
    {
        // Decode the incoming GetMissionStatusCsReq request
        $req = $this->decodeGetMissionStatusCsReq($request);

        // Create the response message
        $response = new GetMissionStatusScRsp();
        $response->setFinishedMainMissionIdList($req->getMainMissionIdList());

        // Populate sub_mission_status_list with Mission objects
        $subMissionStatusList = [];
        foreach ($req->getSubMissionIdList() as $id) {
            $mission = new Mission();
            $mission->setId($id);
            $mission->setProgress(1);
            $mission->setStatus(MissionStatus::MISSION_FINISH);
            $subMissionStatusList[] = $mission;
        }

        $response->setSubMissionStatusList($subMissionStatusList);
        $response->setRetcode(0);

        $this->session->sendPacket($socket, $response);

        // CDD test
        $info_dialog = "Q1MuTWlIb1lvLlNESy5OZXR3b3JrTWFuYWdlci5TaG93TmV0d29ya0Vycm9yKDAsIldlbGNvbWUgdG8gUEhQLVNSXG5cdFNvbWUgc2VydmVyIGZvciB0cmFpbiBnYW1lLlxuXHRUaGlzIGlzIGxhc3QgdXBkYXRlLCBhIG5ldyByZXdyaXR0ZW4gdmVyc2lvbiB3aWxsIGNvbWUhLiIpOw==";

        $cdd = new \ClientDownloadData();
        $cdd->setTime(timestamp::cur_timestamp_ms());
        $cdd->setVersion(51);
        $cdd->setData(base64_decode($info_dialog)); 

        $notify = new \ClientDownloadDataScNotify();
        $notify->setDownloadData($cdd);

        $this->session->sendPacket($socket, $notify);
    }

    /**
     * Decode the GetMissionStatusCsReq protobuf message from the request.
     * 
     * @param Message $request The incoming request data.
     * @return GetMissionStatusCsReq The decoded request message.
     */
    private function decodeGetMissionStatusCsReq(Message $request): GetMissionStatusCsReq
    {
        $req = new GetMissionStatusCsReq();
        $req->mergeFromString($request->serializeToString());
        return $req;
    }
}
