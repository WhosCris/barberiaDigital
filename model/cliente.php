<?php
require_once 'Usuario.php';
require_once 'interfaces/IProfile.php';

/**
 * Clase Cliente
 * Representa un cliente del sistema (tipo_usuario = 2)
 */
class Cliente extends Usuario implements IProfile {
    private $historialReservas = [];

    /**
     * Actualiza los datos básicos del cliente en la base de datos
     * @return bool
     */
    public function actualizarPerfil() {
        $query = "UPDATE usuario 
                  SET nombre = :nombre, email = :email, telefono = :telefono
                  WHERE id_usuario = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Implementación de IProfile
    public function crearPerfil() {
        // Lógica para crear perfil de cliente
        return true;
    }

    public function obtenerPerfil() {
        $query = "SELECT * FROM usuario WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarDatos($datos) {
        $this->nombre = $datos['nombre'] ?? $this->nombre;
        $this->email = $datos['email'] ?? $this->email;
        $this->telefono = $datos['telefono'] ?? $this->telefono;
        return $this->actualizarPerfil();
    }

    // ----------------------------------------------------------------------
    // MÉTODOS ESPECÍFICOS DE CLIENTE
    // ----------------------------------------------------------------------

    public function realizarReserva($datosReserva) {
        // Aquí idealmente llamas a tu reservaModel para insertar reserva real
        return true;
    }

    public function cancelarReserva($idReserva) {
        $query = "UPDATE reserva 
                  SET estado_reserva = 'Cancelada' 
                  WHERE id_reserva = :id AND id_cliente = :cliente_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $idReserva);
        $stmt->bindParam(':cliente_id', $this->id);
        return $stmt->execute();
    }

    public function verMisReservas() {
        $query = "SELECT r.*, s.nombre_servicio, s.precio 
                  FROM reserva r
                  INNER JOIN servicio s ON r.id_servicio = s.id_servicio
                  WHERE r.id_cliente = :cliente_id
                  ORDER BY r.fecha DESC, r.hora DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente_id', $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
