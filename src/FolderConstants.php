<?php

namespace AstralPHP;

class FolderConstants {
    public const DATA_FOLDER = __DIR__ . '/../data/';
    public const STARRAIL_DATA = __DIR__ . '/../StarRailData/';

    public static function getExcelOutput($path): String
    {
        return self::STARRAIL_DATA . "ExcelOutput/$path";
    }

    public static function getRootFolder(): string
    {
        return realpath(__DIR__ . '/../') . '/';
    }
}