<?php

namespace AstralPHP\game\net\handlers\auth;

use Google\Protobuf\Internal\Message;
use AstralPHP\game\net\PlayerSession;
use React\Socket\ConnectionInterface;
class OnPlayerLoginFinish
{
    public function __construct(private PlayerSession $session) {}

    public function handle(ConnectionInterface $socket, Message $request): void
    {
        $response = new \PlayerLoginFinishScRsp();
        $response->setRetcode(0);

        $this->session->sendPacket($socket, $response);

        $content = [
            200001, 200002, 200003, 200004, 200005, 200006,
            150017, 150015, 150021, 150018, 130011, 130012, 
            130013, 150025, 140006, 150026
        ];

        $content_package_list = array_map(function ($id_) {
            $package_info = new \ContentPackageInfo();
            $package_info->setContentId($id_);
            $package_info->setStatus(\ContentPackageStatus::ContentPackageStatus_Finished); // Assuming Finished is an enum value
            return $package_info;
        }, $content);

        $data = new \ContentPackageData();
        $data->setCurContentId(0);
        $data->setContentPackageList($content_package_list);

        $content_data = new \ContentPackageSyncDataScNotify();
        $content_data->setData($data);


        $this->session->sendPacket($socket, $content_data);
    }
}
