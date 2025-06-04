<?php

namespace AstralPHP\common;

class Logger
{
    public const BOLD = "\x1b[1m";

    public const ITALIC = "\x1b[3m";

    public const UNDERLINE = "\x1b[4m";

    public const STRIKETHROUGH = "\x1b[9m";

    public const RESET = "\x1b[m";

    public const BLACK = "\x1b[38;5;16m";

    public const DARK_BLUE = "\x1b[38;5;19m";

    public const DARK_GREEN = "\x1b[38;5;34m";

    public const DARK_AQUA = "\x1b[38;5;37m";

    public const DARK_RED = "\x1b[38;5;124m";

    public const PURPLE = "\x1b[38;5;127m";

    public const GOLD = "\x1b[38;5;214m";

    public const GRAY = "\x1b[38;5;145m";

    public const DARK_GRAY = "\x1b[38;5;59m";

    public const BLUE = "\x1b[38;5;63m";

    public const GREEN = "\x1b[38;5;83m";

    public const AQUA = "\x1b[38;5;87m";

    public const RED = "\x1b[38;5;203m";

    public const LIGHT_PURPLE = "\x1b[38;5;207m";

    public const YELLOW = "\x1b[38;5;227m";

    public const WHITE = "\x1b[38;5;231m";

    public const MINECOIN_GOLD = "\x1b[38;5;184m";

    public static function log(string $content): void
    {
        self::send($content, self::formatHeader(self::GREEN, "Log", false));
    }

    public static function log_excel(string $content): void
    {
        self::send($content, self::formatHeader(self::LIGHT_PURPLE, "ExcelManager", false));
    }    

    public static function log_dispatch(string $content): void
    {
        self::send($content, self::formatHeader(self::GOLD, "Dispatch", false));
    }
    
    public static function log_gameserver(string $content): void
    {
        self::send($content, self::formatHeader(self::AQUA, "Gameserver", false));
    }
    
    public static function log_packet(string $content): void
    {
        self::send($content, self::formatHeader(self::YELLOW, "Packet", false));
    }
    
    public static function notice(string $content): void
    {
        self::send($content, self::formatHeader(self::YELLOW, "NOTICE"));
    }
    
    public static function dummy(string $content): void
    {
        self::send($content, self::formatHeader(self::BLUE, "Dummy", false));
    }
    
    public static function error(string $content): void
    {
        self::send($content, self::formatHeader(self::RED, "ERROR"));
    }
    
    private static function send(string $content, string $header): void
    {
        echo $header . " " . self::RESET . $content . self::RESET . PHP_EOL;
    }
    
    /**
     * Formats the header with the log type and calling file.
     */
    private static function formatHeader(string $color, string $type, bool $showFilePath = true): string
    {
        $fileInfo = '';
    
        if ($showFilePath) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
            $callerFilePath = 'unknown';
    
            foreach ($backtrace as $frame) {
                if (isset($frame['file']) && str_contains($frame['file'], 'src')) {
                    $callerFilePath = $frame['file'];
                    break;
                }
            }
    
            $basePath = dirname(__DIR__, 2);
            $relativePath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $callerFilePath);
            $fileInfo = " || " . $relativePath;
        }
    
        return $color . "[" . $type . $fileInfo . "]" . self::RESET;
    }       
}