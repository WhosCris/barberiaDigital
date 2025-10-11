<?php
require_once 'config/database.php';

class BarberoModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todos los barberos activos
    public function obtenerBarberos() {
        $query = "SELECT id, nombre FROM barberos WHERE activo = 1 ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los servicios
    public function obtenerServicios() {
        $query = "SELECT id, nombre, precio, duracion FROM servicios WHERE activo = 1 ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>