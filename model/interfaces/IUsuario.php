<?php
/**
 * Interface IUsuario
 * Define el contrato que deben cumplir todos los tipos de usuario
 */
interface IUsuario {
    /**
     * Autenticar usuario en el sistema
     * @return boolean
     */
    public function login();
    
    /**
     * Cerrar sesión del usuario
     * @return void
     */
    public function logout();
    
    /**
     * Actualizar información del perfil
     * @return void
     */
    public function actualizarPerfil();
    
    /**
     * Obtener ID del usuario
     * @return int
     */
    public function getId();
    
    /**
     * Obtener nombre del usuario
     * @return string
     */
    public function getNombre();
    
    /**
     * Obtener email del usuario
     * @return string
     */
    public function getEmail();
}
?>