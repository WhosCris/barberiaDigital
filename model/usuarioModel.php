<?php
require_once 'interfaces/IUsuario.php';

/* Base para todos los tipos de usuario del sistema */
abstract class Usuario implements IUsuario {
    protected $id;
    protected $nombre;
    protected $email;
    protected $password;
    protected $telefono;
    protected $conn;
    
    public function __construct($conn, $datos = []) {
        $this->conn = $conn;
        if (!empty($datos)) {
            $this->id = $datos['id_usuario'] ?? null;
            $this->nombre = $datos['nombre'] ?? '';
            $this->email = $datos['email'] ?? '';
            $this->password = $datos['password'] ?? '';
            $this->telefono = $datos['telefono'] ?? '';
        }
    }

    // Implementación básica de IUsuario
    public function login(): bool {
        // Aquí se debería implementar lógica concreta en clases hijas
        return false;
    }

    public function logout(): void {
        session_start();
        session_unset();
        session_destroy();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    // Método abstracto que deben implementar las clases hijas
    abstract public function actualizarPerfil();
}
?>
