<?php
/**
 * Abstract Factory para creación de usuarios
 */
abstract class UsuarioFactory {
    protected $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Método factory para crear usuarios
     * @param array $datos
     * @return IUsuario
     */
    abstract public function crearUsuario($datos);
    
    /**
     * Método factory para crear perfiles
     * @return IProfile
     */
    abstract public function crearPerfil();
}
?>