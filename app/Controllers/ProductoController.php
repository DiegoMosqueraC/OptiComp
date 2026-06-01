<?php

namespace App\Controllers;

use App\Repositories\ProductoRepository;
use App\Helpers\Validador;
use App\Core\Logger;

class ProductoController
{
    private ProductoRepository $repo;

    public function __construct()
    {
        $this->repo = new ProductoRepository();
    }

    public function index(): void
    {
        $productos = $this->repo->obtenerTodos();
        require __DIR__ . '/../Views/productos/index.php';
    }

    public function crear(): void
    {
        $errores    = [];
        $categorias = $this->repo->obtenerCategorias();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descripcion = Validador::texto($_POST['descripcion'] ?? '');
            $categoriaId = Validador::entero($_POST['categoria_id'] ?? 0) ?: null;
            if (empty($descripcion)) {
                $errores[] = 'La descripción del producto es obligatoria.';
            } else {
                $id = $this->repo->crear($descripcion, $categoriaId);
                Logger::logEvent('OPERACION', "Producto #{$id} registrado vía formulario.");
                header('Location: ' . BASE_URL . '/productos');
                exit;
            }
        }
        require __DIR__ . '/../Views/productos/crear.php';
    }

    public function eliminar(int $id): void
    {
        $this->repo->eliminar($id);
        header('Location: ' . BASE_URL . '/productos');
        exit;
    }
}
