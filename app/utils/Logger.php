<?php
namespace App\Utils;

class Logger
{
    private static $logFile = __DIR__ . '/../../logs/app.log';

    public static function log($message)
    {
        // Crée le dossier /logs s'il n'existe pas
        $dir = dirname(self::$logFile);
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        $date = date('Y-m-d H:i:s');
        $line = "[$date] $message" . PHP_EOL;

        file_put_contents(self::$logFile, $line, FILE_APPEND);
    }
}
