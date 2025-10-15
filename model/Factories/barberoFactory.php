<?php
require_once 'UsuarioFactory.php';
require_once __DIR__ . '/../Profesional.php';

class BarberoFactory extends UsuarioFactory {
    
    public function crearUsuario($datos) {
        $query = "INSERT INTO usuario 
                  (nombre, email, password, telefono, id_tipo_usuario, id_peluqueria, estado) 
                  VALUES (:nombre, :email, :password, :telefono, 3, 1, 'activo')";
        
        $stmt = $this->conn->prepare($query);
        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':telefono', $datos['telefono']);
        
        if ($stmt->execute()) {
            $datos['id_usuario'] = $this->conn->lastInsertId();
            $datos['password'] = $passwordHash;
            return new Profesional($this->conn, $datos);
        }
        
        return null;
    }
    
    public function crearPerfil() {
        return new Profesional($this->conn);
    }
}
?>