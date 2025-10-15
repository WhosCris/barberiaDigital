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
    
    // Implementación de métodos de IUsuario
    public function login() {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['usuario_id'] = $this->id;
        $_SESSION['nombre'] = $this->nombre;
        $_SESSION['email'] = $this->email;
        return true;
    }
    
    public function logout() {
        if (isset($_SESSION)) {
            session_unset();
            session_destroy();
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    // Método abstracto que debe ser implementado por las clases hijas
    abstract public function actualizarPerfil();
}
?>