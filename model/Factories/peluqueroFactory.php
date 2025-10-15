<?php
require_once 'config/database.php';

/**
 * PeluqueroFactory - Singleton Pattern + Factory
 * Punto único de acceso para crear cualquier tipo de usuario
 */
class PeluqueroFactory {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    /**
     * Obtener instancia única (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new PeluqueroFactory();
        }
        return self::$instance;
    }
    
    /**
     * Crear usuario según tipo
     * @param string $tipo ('cliente', 'barbero', 'admin')
     * @param array $datos
     * @return IUsuario|null
     */
    public function crearUsuario($tipo, $datos) {
        require_once 'ClienteFactory.php';
        require_once 'BarberoFactory.php';
        require_once 'AdminFactory.php';
        
        $factory = null;
        
        switch(strtolower($tipo)) {
            case 'cliente':
                $factory = new ClienteFactory($this->conn);
                break;
            case 'barbero':
            case 'profesional':
                $factory = new BarberoFactory($this->conn);
                break;
            case 'admin':
            case 'administrador':
                $factory = new AdminFactory($this->conn);
                break;
            default:
                return null;
        }
        
        return $factory ? $factory->crearUsuario($datos) : null;
    }
    
    /**
     * Crear perfil según tipo
     */
    public function crearPerfil($tipo) {
        require_once 'ClienteFactory.php';
        require_once 'BarberoFactory.php';
        require_once 'AdminFactory.php';
        
        $factory = null;
        
        switch(strtolower($tipo)) {
            case 'cliente':
                $factory = new ClienteFactory($this->conn);
                break;
            case 'barbero':
            case 'profesional':
                $factory = new BarberoFactory($this->conn);
                break;
            case 'admin':
            case 'administrador':
                $factory = new AdminFactory($this->conn);
                break;
        }
        
        return $factory ? $factory->crearPerfil() : null;
    }
    
    // Prevenir clonación
    private function __clone() {}
    
    // Prevenir unserialización
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>