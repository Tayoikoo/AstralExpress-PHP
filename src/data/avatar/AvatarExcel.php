<?php

namespace AstralPHP\data\avatar;

use AstralPHP\common\Logger;
use AstralPHP\FolderConstants;

class AvatarExcel
{
    public static function load(): array
    {
        $avatars = [];
        $avatarExcelConfig = json_decode(
            file_get_contents(
                FolderConstants::getExcelOutput("AvatarConfig.json")
            ),
            true
        );

        $count = 0;

        foreach ($avatarExcelConfig as $avatarConfig) {
            if (isset($avatarConfig['AvatarID'])) {
                $avatar = new AvatarConfig($avatarConfig);
                $avatars[] = $avatar;
                $count++;
            }
        }

        Logger::log_excel("Loaded " . $count . " AvatarConfigs");
        return $avatars;
    }
}
