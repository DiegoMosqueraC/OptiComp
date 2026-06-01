<?php

namespace App\Helpers;

/**
 * Validador - Helper de validación y sanitización
 *
 * REFACTORING APLICADO:
 *   - Move Method: antes había dos archivos validador.php (app/Core y app/Data) — DUPLICACIÓN eliminada
 *   - Rename: clase ubicada en namespace App\Helpers y en carpeta Helpers
 *   - Extract Method: validateTicketForm() y validateClienteForm() — lógica de validación
 *     que antes estaba dispersa en controladores, ahora centralizada aquí
 */
class Validador
{
    public static function texto(string $dato): string
    {
        return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
    }

    public static function entero(mixed $dato): int|false
    {
        return filter_var($dato, FILTER_VALIDATE_INT);
    }

    public static function email(string $dato): string|false
    {
        return filter_var(trim($dato), FILTER_VALIDATE_EMAIL);
    }

    public static function requerido(mixed $dato): bool
    {
        return isset($dato) && trim((string)$dato) !== '';
    }

    /**
     * EXTRACT METHOD: validación completa del formulario de ticket.
     * Retorna array de errores (vacío = válido).
     */
    public static function validateTicketForm(array $data): array
    {
        $errores = [];

        if (!self::requerido($data['cliente_id'] ?? '')) {
            $errores[] = 'El cliente es obligatorio.';
        } elseif (self::entero($data['cliente_id']) === false) {
            $errores[] = 'El cliente debe ser un número válido.';
        }

        if (!self::requerido($data['equipo'] ?? '')) {
            $errores[] = 'El equipo es obligatorio.';
        }

        if (!self::requerido($data['descripcion'] ?? '')) {
            $errores[] = 'La descripción es obligatoria.';
        }

        if (!self::requerido($data['estado'] ?? '')) {
            $errores[] = 'El estado es obligatorio.';
        }

        return $errores;
    }

    /**
     * EXTRACT METHOD: validación completa del formulario de cliente.
     */
    public static function validateClienteForm(array $data): array
    {
        $errores = [];

        if (!self::requerido($data['nombre'] ?? '')) {
            $errores[] = 'El nombre es obligatorio.';
        }

        if (!self::requerido($data['tp_doc'] ?? '')) {
            $errores[] = 'El tipo de documento es obligatorio.';
        }

        if (!self::requerido($data['telefono'] ?? '')) {
            $errores[] = 'El teléfono es obligatorio.';
        }

        if (!self::requerido($data['email'] ?? '') || self::email($data['email'] ?? '') === false) {
            $errores[] = 'El email es obligatorio y debe ser válido.';
        }

        return $errores;
    }
}
