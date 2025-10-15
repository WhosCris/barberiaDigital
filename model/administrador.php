<?php
require_once 'Usuario.php';
require_once 'interfaces/IProfile.php';

/**
 * Clase Administrador
 * Representa un administrador del sistema (tipo_usuario = 1)
 */
class Administrador extends Usuario implements IProfile {
    
    public function actualizarPerfil() {
        $query = "UPDATE usuario 
                  SET nombre = :nombre, email = :email, telefono = :telefono
                  WHERE id_usuario = :id AND id_tipo_usuario = 1";
        
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
        $query = "SELECT * FROM usuario WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function actualizarDatos($datos) {
        $this->nombre = $datos['nombre'] ?? $this->nombre;
        $this->email = $datos['email'] ?? $this->email;
        $this->telefono = $datos['telefono'] ?? $this->telefono;
        return $this->actualizarPerfil();
    }
    
    // Métodos específicos de Administrador
    public function registrarProfesional($datos) {
        $query = "INSERT INTO usuario 
                  (nombre, email, password, telefono, id_tipo_usuario, id_peluqueria, estado) 
                  VALUES (:nombre, :email, :password, :telefono, 3, 1, 'activo')";
        
        $stmt = $this->conn->prepare($query);
        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':telefono', $datos['telefono']);
        
        return $stmt->execute();
    }
    
    public function eliminarProfesional($idProfesional) {
        $query = "UPDATE usuario SET estado = 'inactivo' 
                  WHERE id_usuario = :id AND id_tipo_usuario = 3";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $idProfesional);
        return $stmt->execute();
    }
    
    public function gestionarHorarios($idBarbero, $horarios) {
        // Lógica para gestionar horarios
        return true;
    }
    
    public function gestionarServicios($operacion, $datos) {
        // Lógica para gestionar servicios
        return true;
    }
}
?>