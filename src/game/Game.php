<?php

namespace AstralPHP\game;

use AstralPHP\common\Logger;
use AstralPHP\game\net\Gateway;

class Game {
    public static function init(): void
    {
        $gateway = new Gateway('0.0.0.0', 23301);
        $gateway->listen();
    }
}