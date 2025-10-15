<?php
require_once 'UsuarioFactory.php';
require_once __DIR__ . '/../Cliente.php';

class ClienteFactory extends UsuarioFactory {
    
    public function crearUsuario($datos) {
        // Crear cliente en la base de datos
        $query = "INSERT INTO usuario 
                  (nombre, email, password, telefono, id_tipo_usuario, estado) 
                  VALUES (:nombre, :email, :password, :telefono, 2, 'activo')";
        
        $stmt = $this->conn->prepare($query);
        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':telefono', $datos['telefono']);
        
        if ($stmt->execute()) {
            $datos['id_usuario'] = $this->conn->lastInsertId();
            $datos['password'] = $passwordHash;
            return new Cliente($this->conn, $datos);
        }
        
        return null;
    }
    
    public function crearPerfil() {
        // Retorna una instancia de Cliente para operaciones de perfil
        return new Cliente($this->conn);
    }
}
?>