
<?php
require_once 'config/database.php';

class barberoModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todos los barberos activos (tipo_usuario = 3)
    public function obtenerBarberos() {
        $query = "SELECT u.id_usuario as id, u.nombre 
                  FROM usuario u 
                  WHERE u.id_tipo_usuario = 3 
                  AND u.estado = 'activo' 
                  ORDER BY u.nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los servicios activos
    public function obtenerServicios() {
        $query = "SELECT id_servicio as id, nombre_servicio as nombre, precio, duracion 
                  FROM servicio 
                  WHERE id_peluqueria = 1 
                  ORDER BY nombre_servicio";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener barbero por ID
    public function obtenerBarberoPorId($id) {
        $query = "SELECT u.id_usuario, u.nombre, u.email 
                  FROM usuario u 
                  WHERE u.id_usuario = :id 
                  AND u.id_tipo_usuario = 3";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>