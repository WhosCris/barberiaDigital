<?php
require_once 'config/database.php';

class ReservaModel {
    private $conn;
    private $table = 'reservas';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Validar barbero
    public function validarBarbero($barbero_id) {
        if (empty($barbero_id)) {
            return ['valido' => false, 'mensaje' => 'Debes seleccionar un barbero'];
        }
        return ['valido' => true];
    }

    // Validar servicio
    public function validarServicio($servicio_id) {
        if (empty($servicio_id)) {
            return ['valido' => false, 'mensaje' => 'Debes seleccionar un servicio'];
        }
        return ['valido' => true];
    }

    // Validar fecha
    public function validarFecha($fecha) {
        if (empty($fecha)) {
            return ['valido' => false, 'mensaje' => 'Debes seleccionar una fecha'];
        }
        
        $fechaSeleccionada = new DateTime($fecha);
        $hoy = new DateTime();
        $hoy->setTime(0, 0, 0);
        
        if ($fechaSeleccionada < $hoy) {
            return ['valido' => false, 'mensaje' => 'La fecha no puede ser anterior a hoy'];
        }
        
        return ['valido' => true];
    }

    // Validar hora
    public function validarHora($hora) {
        if (empty($hora)) {
            return ['valido' => false, 'mensaje' => 'Debes seleccionar una hora'];
        }
        return ['valido' => true];
    }

    // Validar nombre
    public function validarNombre($nombre) {
        if (empty(trim($nombre)) || strlen(trim($nombre)) < 2) {
            return ['valido' => false, 'mensaje' => 'El nombre debe tener al menos 2 caracteres'];
        }
        return ['valido' => true];
    }

    // Validar email
    public function validarEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valido' => false, 'mensaje' => 'Por favor ingresa un email válido'];
        }
        return ['valido' => true];
    }

    // Verificar disponibilidad
    public function verificarDisponibilidad($barbero_id, $fecha, $hora) {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE barbero_id = :barbero_id 
                  AND fecha = :fecha 
                  AND hora = :hora 
                  AND estado != 'cancelada'
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':barbero_id', $barbero_id);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->execute();
        
        return $stmt->rowCount() === 0;
    }

    // Crear reserva
    public function crearReserva($datos) {
        // Verificar disponibilidad primero
        if (!$this->verificarDisponibilidad($datos['barbero_id'], $datos['fecha'], $datos['hora'])) {
            return ['success' => false, 'mensaje' => 'Esta hora ya no está disponible'];
        }

        $query = "INSERT INTO " . $this->table . " 
                  (barbero_id, servicio_id, fecha, hora, nombre_cliente, email_cliente, estado) 
                  VALUES (:barbero_id, :servicio_id, :fecha, :hora, :nombre_cliente, :email_cliente, 'confirmada')";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':barbero_id', $datos['barbero_id']);
        $stmt->bindParam(':servicio_id', $datos['servicio_id']);
        $stmt->bindParam(':fecha', $datos['fecha']);
        $stmt->bindParam(':hora', $datos['hora']);
        $stmt->bindParam(':nombre_cliente', $datos['nombre_cliente']);
        $stmt->bindParam(':email_cliente', $datos['email_cliente']);
        
        if ($stmt->execute()) {
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
        }
        return ['success' => false, 'mensaje' => 'Error al crear la reserva'];
    }

    // Obtener horas disponibles
    public function obtenerHorasDisponibles($barbero_id, $fecha) {
        $horasLaborales = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '14:00', '14:30', '15:00', '15:30',
            '16:00', '16:30', '17:00', '17:30', '18:00', '18:30'
        ];

        $query = "SELECT hora FROM " . $this->table . " 
                  WHERE barbero_id = :barbero_id 
                  AND fecha = :fecha 
                  AND estado != 'cancelada'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':barbero_id', $barbero_id);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        
        $horasOcupadas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $horasOcupadas[] = $row['hora'];
        }
        
        $horasDisponibles = array_diff($horasLaborales, $horasOcupadas);
        return array_values($horasDisponibles);
    }
}
?>