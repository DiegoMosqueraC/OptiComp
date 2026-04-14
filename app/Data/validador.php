<?php

class Validador {

    public static function texto($dato) {
        return filter_var(trim($dato), FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public static function entero($dato) {
        return filter_var($dato, FILTER_VALIDATE_INT);
    }

    public static function email($dato) {
        return filter_var($dato, FILTER_VALIDATE_EMAIL);
    }

    public static function requerido($dato) {
        return isset($dato) && trim($dato) !== '';
    }
}
