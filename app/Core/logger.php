<?php

namespace App\Core;

class Logger
{
    private static $logFile = __DIR__ . '/../../logs/audit.log';

    public static function logEvent(string $tipoEvento, string $mensajeDetallado): void
    {
        $logDir = dirname(self::$logFile);
        
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $fechaHora = date('Y-m-d H:i:s');
        $lineaLog = sprintf("[%s] [%s] %s" . PHP_EOL, $fechaHora, strtoupper($tipoEvento), $mensajeDetallado);
        
        file_put_contents(self::$logFile, $lineaLog, FILE_APPEND);
    }
}

?>