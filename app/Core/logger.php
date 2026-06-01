<?php

namespace App\Core;

/**
 * Clase Logger - Componente de Auditoría (Guía 9)
 *
 * REFACTORING APLICADO:
 *   - Rename: log() -> logEvent() (nombre descriptivo, evita conflicto con funciones nativas)
 *   - Extract Method: formatLine() y ensureLogDirectory() separados
 *   - Responsabilidad única: solo escritura de log
 */
class Logger
{
    private static string $logFile = '';

    private static function getLogPath(): string
    {
        if (self::$logFile === '') {
            $config = require __DIR__ . '/../../config/app.php';
            self::$logFile = $config['log_path'];
        }

        return self::$logFile;
    }

    public static function logEvent(string $tipoEvento, string $mensajeDetallado): void
    {
        self::ensureLogDirectory();
        $linea = self::formatLine($tipoEvento, $mensajeDetallado);
        file_put_contents(self::getLogPath(), $linea, FILE_APPEND | LOCK_EX);
    }

    /**
     * EXTRACT METHOD: formateo de la línea de log.
     */
    private static function formatLine(string $tipo, string $mensaje): string
    {
        $fechaHora = date('Y-m-d H:i:s');
        return sprintf("[%s] [%s] %s" . PHP_EOL, $fechaHora, strtoupper($tipo), $mensaje);
    }

    /**
     * EXTRACT METHOD: verificación/creación del directorio de logs.
     */
    private static function ensureLogDirectory(): void
    {
        $dir = dirname(self::getLogPath());
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
