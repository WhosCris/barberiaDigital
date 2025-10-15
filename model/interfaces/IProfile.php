<?php
/**
 * Interface IProfile
 * Define operaciones relacionadas con el perfil de usuario
 */
interface IProfile {
    /**
     * Crear perfil de usuario
     * @return void
     */
    public function crearPerfil();
    
    /**
     * Obtener datos del perfil
     * @return array
     */
    public function obtenerPerfil();
    
    /**
     * Actualizar datos del perfil
     * @param array $datos
     * @return boolean
     */
    public function actualizarDatos($datos);
}
?>