<?php

namespace App\Models;

/**
 * Modelo Producto - Entidad de Negocio
 */
class Producto
{
    private ?int $id = null;
    private string $descripcion;
    private ?int $categoriaId;

    public function __construct(string $descripcion, ?int $categoriaId = null)
    {
        $this->descripcion = $descripcion;
        $this->categoriaId = $categoriaId;
    }

    public function getId(): ?int           { return $this->id; }
    public function getDescripcion(): string { return $this->descripcion; }
    public function getCategoriaId(): ?int  { return $this->categoriaId; }

    public function setId(int $id): void              { $this->id = $id; }
    public function setDescripcion(string $d): void   { $this->descripcion = $d; }
    public function setCategoriaId(?int $cid): void   { $this->categoriaId = $cid; }

    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'descripcion'  => $this->descripcion,
            'categoria_id' => $this->categoriaId,
        ];
    }
}
