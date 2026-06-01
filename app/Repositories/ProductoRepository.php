<?php

namespace App\Repositories;

use PDO;
use PDOException;
use App\Core\Conexion;
use App\Core\Logger;

/**
 * ProductoRepository - Acceso a datos de la entidad Producto
 */
class ProductoRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Conexion::getInstancia()->getConexion();
    }

    public function obtenerTodos(): array
    {
        $stmt = $this->db->query(
            "SELECT p.*, c.detalle AS categoria_nombre
             FROM producto p
             LEFT JOIN categoria c ON p.categoria_id = c.cod_categoria
             ORDER BY p.id ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.detalle AS categoria_nombre
             FROM producto p
             LEFT JOIN categoria c ON p.categoria_id = c.cod_categoria
             WHERE p.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function crear(string $descripcion, ?int $categoriaId): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO producto (descripcion, categoria_id) VALUES (:descripcion, :categoria_id)"
        );
        $stmt->execute([':descripcion' => $descripcion, ':categoria_id' => $categoriaId]);
        $id = (int) $this->db->lastInsertId();
        Logger::logEvent('OPERACION', "Producto creado con ID: {$id}");
        return $id;
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM producto WHERE id = :id");
        $result = $stmt->execute([':id' => $id]);
        Logger::logEvent('OPERACION', "Producto ID {$id} eliminado.");
        return $result;
    }

    public function obtenerCategorias(): array
    {
        $stmt = $this->db->query("SELECT * FROM categoria ORDER BY cod_categoria ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
