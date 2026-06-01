<?php

namespace App\Models;

/**
 * Modelo Ticket - Entidad de Negocio
 *
 * REFACTORING APLICADO:
 *   - Rename: constructor param $descripcion_problema -> $descripcion (consistencia PSR-12)
 *   - Clase limpia: solo propiedades + getters/setters (sin lógica de persistencia)
 *   - Typed properties (PHP 8.x)
 */
class Ticket
{
    private ?int $id = null;
    private int $clienteId;
    private string $equipo;
    private string $descripcion;
    private string $estado;
    private string $fechaIngreso;
    private ?string $fechaSalida = null;

    public function __construct(
        int $clienteId,
        string $equipo,
        string $descripcion,
        string $estado,
        string $fechaIngreso
    ) {
        $this->clienteId    = $clienteId;
        $this->equipo       = $equipo;
        $this->descripcion  = $descripcion;
        $this->estado       = $estado;
        $this->fechaIngreso = $fechaIngreso;
    }

    public function getId(): ?int         { return $this->id; }
    public function getClienteId(): int   { return $this->clienteId; }
    public function getEquipo(): string   { return $this->equipo; }
    public function getDescripcion(): string { return $this->descripcion; }
    public function getEstado(): string   { return $this->estado; }
    public function getFechaIngreso(): string { return $this->fechaIngreso; }
    public function getFechaSalida(): ?string { return $this->fechaSalida; }

    public function setId(int $id): void              { $this->id = $id; }
    public function setClienteId(int $id): void       { $this->clienteId = $id; }
    public function setEquipo(string $equipo): void   { $this->equipo = $equipo; }
    public function setDescripcion(string $d): void   { $this->descripcion = $d; }
    public function setEstado(string $estado): void   { $this->estado = $estado; }
    public function setFechaIngreso(string $f): void  { $this->fechaIngreso = $f; }
    public function setFechaSalida(?string $f): void  { $this->fechaSalida = $f; }

    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'cliente_id'    => $this->clienteId,
            'equipo'        => $this->equipo,
            'descripcion'   => $this->descripcion,
            'estado'        => $this->estado,
            'fecha_ingreso' => $this->fechaIngreso,
            'fecha_salida'  => $this->fechaSalida,
        ];
    }
}
