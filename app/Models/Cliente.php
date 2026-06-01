<?php

namespace App\Models;

/**
 * Modelo Cliente - Entidad de Negocio
 */
class Cliente
{
    private ?int $idCliente = null;
    private string $tipoDocumento;
    private string $nombre;
    private string $telefono;
    private string $email;

    public function __construct(
        string $tipoDocumento,
        string $nombre,
        string $telefono,
        string $email
    ) {
        $this->tipoDocumento = $tipoDocumento;
        $this->nombre        = $nombre;
        $this->telefono      = $telefono;
        $this->email         = $email;
    }

    public function getIdCliente(): ?int        { return $this->idCliente; }
    public function getTipoDocumento(): string  { return $this->tipoDocumento; }
    public function getNombre(): string        { return $this->nombre; }
    public function getTelefono(): string      { return $this->telefono; }
    public function getEmail(): string         { return $this->email; }

    public function setIdCliente(int $id): void { $this->idCliente = $id; }

    public function toArray(): array
    {
        return [
            'id_cliente'  => $this->idCliente,
            'tp_doc'      => $this->tipoDocumento,
            'nombre'      => $this->nombre,
            'telefono'    => $this->telefono,
            'email'       => $this->email,
        ];
    }
}
