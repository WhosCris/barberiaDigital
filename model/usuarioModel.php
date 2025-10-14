<?php
require_once 'config/database.php';

class usuarioModel {
    private $conn;
    private $table = 'usuario';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Autenticar usuario
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

    public function obtenerAdminPorEmail($email) {
    $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE email = :email AND id_tipo_usuario = 1 LIMIT 1");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verificar si email existe
    public function emailExiste($email) {
        $query = "SELECT id_usuario FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    // Verificar si email existe para otro usuario
public function emailExisteOtroUsuario($email, $usuario_id) {
    $query = "SELECT id_usuario FROM " . $this->table . " 
              WHERE email = :email AND id_usuario != :usuario_id 
              LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->execute();
    return $stmt->rowCount() > 0;
}

// Actualizar perfil de usuario
public function actualizarPerfil($datos) {
    try {
        // Si hay contraseña nueva, verificar la actual primero
        if (!empty($datos['password_nueva'])) {
            $query = "SELECT password FROM " . $this->table . " WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $datos['id']);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!password_verify($datos['password_actual'], $usuario['password'])) {
                return [
                    'success' => false,
                    'mensaje' => 'La contraseña actual es incorrecta'
                ];
            }
            
            // Actualizar con nueva contraseña
            $query = "UPDATE " . $this->table . " 
                      SET nombre = :nombre, email = :email, telefono = :telefono, password = :password
                      WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($query);
            $passwordHash = password_hash($datos['password_nueva'], PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $passwordHash);
        } else {
            // Actualizar sin cambiar contraseña
            $query = "UPDATE " . $this->table . " 
                      SET nombre = :nombre, email = :email, telefono = :telefono
                      WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($query);
        }
        
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':id', $datos['id']);
        
        if ($stmt->execute()) {
            return ['success' => true];
        }
        
        return ['success' => false, 'mensaje' => 'Error al actualizar perfil'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
    }
}

    // Obtener usuario por ID
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

    // Registrar nuevo administrador
public function registrarAdministrador($datos) {
    try {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, email, password, telefono, id_tipo_usuario, id_peluqueria, estado) 
                  VALUES (:nombre, :email, :password, :telefono, 1, 1, 'activo')";
        
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
            'mensaje' => 'Error al registrar administrador'
        ];
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'mensaje' => 'Error: ' . $e->getMessage()
        ];
    }
}
}
?>