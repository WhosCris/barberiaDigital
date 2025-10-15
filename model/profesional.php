<?php
require_once 'Usuario.php';
require_once 'interfaces/IProfile.php';

/**
 * Clase Profesional (Barbero)
 * Representa un barbero del sistema (tipo_usuario = 3)
 */
class Profesional extends Usuario implements IProfile {
    private $especialidad;
    private $horariosDisponibles = [];
    
    public function __construct($conn, $datos = []) {
        parent::__construct($conn, $datos);
        $this->especialidad = $datos['especialidad'] ?? 'General';
    }
    
    public function actualizarPerfil() {
        $query = "UPDATE usuario 
                  SET nombre = :nombre, email = :email, telefono = :telefono
                  WHERE id_usuario = :id AND id_tipo_usuario = 3";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }
    
    // Implementación de IProfile
    public function crearPerfil() {
        return true;
    }
    
    public function obtenerPerfil() {
        $query = "SELECT u.*, p.nombre_peluqueria 
                  FROM usuario u
                  LEFT JOIN peluqueria p ON u.id_peluqueria = p.id_peluqueria
                  WHERE u.id_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function actualizarDatos($datos) {
        $this->nombre = $datos['nombre'] ?? $this->nombre;
        $this->email = $datos['email'] ?? $this->email;
        $this->telefono = $datos['telefono'] ?? $this->telefono;
        $this->especialidad = $datos['especialidad'] ?? $this->especialidad;
        return $this->actualizarPerfil();
    }
    
    // Métodos específicos de Profesional
    public function verAgenda() {
        $query = "SELECT r.*, s.nombre_servicio, u.nombre as cliente_nombre
                  FROM reserva r
                  INNER JOIN servicio s ON r.id_servicio = s.id_servicio
                  INNER JOIN usuario u ON r.id_cliente = u.id_usuario
                  WHERE s.id_barbero = :barbero_id
                  AND r.fecha >= CURDATE()
                  ORDER BY r.fecha ASC, r.hora ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':barbero_id', $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function actualizarDisponibilidad($horarios) {
        // Lógica para actualizar disponibilidad
        $this->horariosDisponibles = $horarios;
        return true;
    }
}
?>