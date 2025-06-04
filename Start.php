<?php
namespace AstralPHP;

require_once __DIR__ . '/vendor/autoload.php';

use AstralPHP\common\Helpers;
use AstralPHP\common\Logger;
use AstralPHP\data\ExcelManager;
use AstralPHP\game\Game;
use AstralPHP\sdkserver\sdkserver;

function run(): void
{
    Helpers::clear_screen();
    Helpers::php_sr();
    sleep(1);

    ExcelManager::init();

    sleep(2);

    $logger = new Logger();
    $server = new SdkServer($logger);
    $server->init();

    sleep(2);
    Game::init();
}

run();