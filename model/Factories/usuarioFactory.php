<?php
require_once 'model/interfaces/IUsuario.php'; 
require_once 'model/interfaces/IProfile.php';
require_once 'model/Administrador.php';
require_once 'model/Cliente.php'; 

class usuarioFactory {
    
    protected $conn; 

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Crear usuario según rol
    public function crearUsuario($datos): IUsuario {
        $rol = $datos['rol'] ?? 'cliente'; 
        switch ($rol) {
            case 'admin':
                return new Administrador($this->conn, $datos);
            case 'cliente':
                return new Cliente($this->conn, $datos);
            default:
                throw new Exception("Rol de usuario desconocido para creación.");
        }
    }

    // Crear perfil (no implementado aún)
    public function crearPerfil(): IProfile {
        throw new Exception("Método crearPerfil no implementado completamente.");
    }

    // Buscar usuario por email y rol (para login de admin)
    public function obtenerUsuarioPorEmailYRol(string $email, string $rol): ?IUsuario {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email AND rol = :rol");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':rol', $rol);
            $stmt->execute();
            $datosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$datosUsuario) return null;

            switch ($rol) {
                case 'admin':
                    return new Administrador($this->conn, $datosUsuario);
                case 'cliente':
                    return new Cliente($this->conn, $datosUsuario);
                default:
                    return null; 
            }
        } catch (PDOException $e) {
            error_log("Error de DB en Factory: " . $e->getMessage());
            return null;
        }
    }
}
?>
