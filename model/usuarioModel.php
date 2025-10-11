<?php
require_once 'config/database.php';

class usuarioModel {
    private $conn;
    private $table = 'usuario';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Autenticar usuario (ya existía)
    public function autenticar($email, $password) {
        $query = "SELECT u.*, t.descripcion as tipo_descripcion 
                  FROM " . $this->table . " u
                  INNER JOIN tipousuario t ON u.id_tipo_usuario = t.id_tipo_usuario
                  WHERE u.email = :email 
                  AND u.estado = 'activo'
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return [
                'success' => false,
                'mensaje' => 'Email o contraseña incorrectos'
            ];
        }

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $usuario['password'])) {
            return [
                'success' => true,
                'usuario' => $usuario
            ];
        } else {
            return [
                'success' => false,
                'mensaje' => 'Email o contraseña incorrectos'
            ];
        }
    }

    // Registrar nuevo cliente
    public function registrarCliente($datos) {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      (nombre, email, password, telefono, id_tipo_usuario, estado) 
                      VALUES (:nombre, :email, :password, :telefono, 2, 'activo')";
            
            $stmt = $this->conn->prepare($query);
            
            // Encriptar contraseña
            $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
            
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':password', $passwordHash);
            $stmt->bindParam(':telefono', $datos['telefono']);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'id' => $this->conn->lastInsertId()
                ];
            }
            
            return [
                'success' => false,
                'mensaje' => 'Error al registrar usuario'
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'mensaje' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Verificar si email existe (ya existía)
    public function emailExiste($email) {
        $query = "SELECT id_usuario FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Obtener usuario por ID (ya existía)
    public function obtenerPorId($id) {
        $query = "SELECT u.*, t.descripcion as tipo_descripcion 
                  FROM " . $this->table . " u
                  INNER JOIN tipousuario t ON u.id_tipo_usuario = t.id_tipo_usuario
                  WHERE u.id_usuario = :id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>