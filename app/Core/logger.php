<?php
// Ubicación: /app/Core/Logger.php

class Logger {
    // Definimos la ruta del archivo de log. Subimos dos niveles (../../) para llegar a la raíz y entrar a /logs/
    private static $logFile = __DIR__ . '/../../logs/audit.log'; 

    public static function log($tipoEvento, $mensajeDetallado) {
        // 1. Asegurarnos de que la carpeta /logs/ exista. Si no, la creamos.
        $logDir = dirname(self::$logFile);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }

        // 2. Obtener la fecha y hora exacta del evento
        $fechaHora = date('Y-m-d H:i:s');
        
        // 3. Formatear el mensaje según lo exige la guía: [FECHA_HORA] [TIPO_EVENTO] [MENSAJE_DETALLADO]
        $lineaLog = sprintf("[%s] [%s] %s" . PHP_EOL, $fechaHora, strtoupper($tipoEvento), $mensajeDetallado);
        
        // 4. Escribir en el archivo audit.log sin borrar lo anterior (FILE_APPEND)
        file_put_contents(self::$logFile, $lineaLog, FILE_APPEND);
    }
}
?>