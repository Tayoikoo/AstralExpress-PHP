<?php

namespace AstralPHP\data;

use AstralPHP\data\avatar\AvatarExcel;

class ExcelManager {
    public static array $avatars = [];

    public static function init(): void
    {
        self::$avatars = AvatarExcel::load();
    }

    public static function getAvatars(): array
    {
        return self::$avatars;
    }
}
