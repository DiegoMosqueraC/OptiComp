<?php
// Archivo: app/Services/ServiceConnector.php
require_once __DIR__ . '/../Core/conexion.php';

class ServiceConnector {
    
    private $apiUrl = "https://jsonplaceholder.typicode.com/users";

    public function sincronizarClientesExternos() {
        // 1. Consumir el Web Service con cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Manejo de timeouts 

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Manejo de errores de conexión
        if (curl_errno($ch)) {
            return ["status" => "error", "message" => "Error de cURL: " . curl_error($ch)];
        }
        curl_close($ch);

        // Validación de códigos HTTP (ej. 404/500)
        if ($httpCode !== 200) {
            return ["status" => "error", "message" => "Error del servidor externo. Código HTTP: $httpCode"];
        }

        // 2. Mapear datos JSON hacia objetos/arrays manejables
        $clientesExternos = json_decode($response, true);
        $conexion = Conexion::getInstancia()->getConexion();
        $insertados = 0;

        // 3. Persistencia en la Base de Datos (Integración Guía 6)
        try {
            $conexion->beginTransaction();
            // Preparamos la consulta (evita inyecciones y duplicaods básicos)
            $stmt = $conexion->prepare("INSERT IGNORE INTO cliente (id_cliente, tp_doc, nombre, telefono, email) VALUES (?, ?, ?, ?, ?)");

            foreach ($clientesExternos as $cliente) {
                // Mapeamos los datos de la API a las columnas de tu BD
                $id_cliente = $cliente['id'] + 100; // Sumamos 100 para no chocar con tus seeders (1 y 2)
                $tp_doc = 'NIT'; // Documento genérico para empresas importadas
                $nombre = $cliente['name'];
                $telefono = substr($cliente['phone'], 0, 20); // Recortamos por si la API manda datos largos
                $email = $cliente['email'];

                $stmt->execute([$id_cliente, $tp_doc, $nombre, $telefono, $email]);
                $insertados++;
            }
            
            $conexion->commit();
            return ["status" => "success", "message" => "✔ Sincronización exitosa. Se insertaron/actualizaron $insertados clientes desde el Web Service."];

        } catch (PDOException $e) {
            $conexion->rollBack();
            return ["status" => "error", "message" => "Error al guardar en base de datos: " . $e->getMessage()];
        }
    }
}
?>