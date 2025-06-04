<?php
namespace AstralPHP\common;

class Helpers {
    public static function php_sr()
    {
        print("\n");
        print("██████╗ ██╗  ██╗██████╗       ███████╗██████╗ \n");
        print("██╔══██╗██║  ██║██╔══██╗      ██╔════╝██╔══██╗\n");
        print("██████╔╝███████║██████╔╝█████╗███████╗██████╔╝\n");
        print("██╔═══╝ ██╔══██║██╔═══╝ ╚════╝╚════██║██╔══██╗\n");
        print("██║     ██║  ██║██║           ███████║██║  ██║\n");
        print("╚═╝     ╚═╝  ╚═╝╚═╝           ╚══════╝╚═╝  ╚═╝\n");
        print("\n");
    }

    public static function clear_screen(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            echo "\033[2J\033[;H";
        } else {
            echo "\033c";
        }        
    }
}