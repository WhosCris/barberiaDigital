<?php
require_once 'config/database.php';

class reservaModel {
    private $conn;
    private $table = 'reserva';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function validarBarbero($barbero_id) {
        if (empty($barbero_id)) {
            return ['valido' => false, 'mensaje' => 'Debes seleccionar un barbero'];
        }
        return ['valido' => true];
    }

    public function validarServicio($servicio_id) {
        if (empty($servicio_id)) {
            return ['valido' => false, 'mensaje' => 'Debes seleccionar un servicio'];
        }
        return ['valido' => true];
    }

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

    public function validarHora($hora) {
        if (empty($hora)) {
            return ['valido' => false, 'mensaje' => 'Debes seleccionar una hora'];
        }
        return ['valido' => true];
    }

    public function validarNombre($nombre) {
        if (empty(trim($nombre)) || strlen(trim($nombre)) < 2) {
            return ['valido' => false, 'mensaje' => 'El nombre debe tener al menos 2 caracteres'];
        }
        return ['valido' => true];
    }

    public function validarEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valido' => false, 'mensaje' => 'Por favor ingresa un email válido'];
        }
        return ['valido' => true];
    }

    // Verificar disponibilidad de hora
    public function verificarDisponibilidad($servicio_id, $fecha, $hora) {
        $query = "SELECT id_reserva FROM " . $this->table . " 
                  WHERE id_servicio = :servicio_id 
                  AND fecha = :fecha 
                  AND hora = :hora 
                  AND estado_reserva NOT IN ('Cancelada')
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':servicio_id', $servicio_id);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->execute();
        
        return $stmt->rowCount() === 0;
    }

    // Crear reserva 
    public function crearReserva($datos) {
        try {
            $this->conn->beginTransaction();

            // 1. Verificar si el cliente ya existe por email
            $queryCliente = "SELECT id_usuario FROM usuario WHERE email = :email AND id_tipo_usuario = 2";
            $stmtCliente = $this->conn->prepare($queryCliente);
            $stmtCliente->bindParam(':email', $datos['email_cliente']);
            $stmtCliente->execute();
            $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

            if ($cliente) {
                $id_cliente = $cliente['id_usuario'];
            } else {
                // Crear nuevo cliente
                $queryNuevoCliente = "INSERT INTO usuario (nombre, email, password, id_tipo_usuario, estado) 
                                      VALUES (:nombre, :email, :password, 2, 'activo')";
                $stmtNuevo = $this->conn->prepare($queryNuevoCliente);
                $passwordHash = password_hash('123456', PASSWORD_DEFAULT); // Password temporal
                $stmtNuevo->bindParam(':nombre', $datos['nombre_cliente']);
                $stmtNuevo->bindParam(':email', $datos['email_cliente']);
                $stmtNuevo->bindParam(':password', $passwordHash);
                $stmtNuevo->execute();
                $id_cliente = $this->conn->lastInsertId();
            }

            // 2. Verificar disponibilidad
            if (!$this->verificarDisponibilidad($datos['servicio_id'], $datos['fecha'], $datos['hora'])) {
                $this->conn->rollBack();
                return ['success' => false, 'mensaje' => 'Esta hora ya no está disponible'];
            }

            // 3. Crear la reserva
            $query = "INSERT INTO " . $this->table . " 
                      (fecha, hora, estado_reserva, id_servicio, id_cliente) 
                      VALUES (:fecha, :hora, 'Pendiente', :servicio_id, :cliente_id)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':fecha', $datos['fecha']);
            $stmt->bindParam(':hora', $datos['hora']);
            $stmt->bindParam(':servicio_id', $datos['servicio_id']);
            $stmt->bindParam(':cliente_id', $id_cliente);
            
            if ($stmt->execute()) {
                $this->conn->commit();
                return ['success' => true, 'id' => $this->conn->lastInsertId()];
            }

            $this->conn->rollBack();
            return ['success' => false, 'mensaje' => 'Error al crear la reserva'];

        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }

    // Obtener horas disponibles según barbero y fecha
    public function obtenerHorasDisponibles($barbero_id, $fecha) {
        // Horas base de trabajo
        $horasLaborales = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '14:00', '14:30', '15:00', '15:30',
            '16:00', '16:30', '17:00', '17:30', '18:00'
        ];

        // Obtener servicios del barbero
        $queryServicios = "SELECT id_servicio FROM servicio WHERE id_barbero = :barbero_id";
        $stmtServ = $this->conn->prepare($queryServicios);
        $stmtServ->bindParam(':barbero_id', $barbero_id);
        $stmtServ->execute();
        $servicios = $stmtServ->fetchAll(PDO::FETCH_COLUMN);

        if (empty($servicios)) {
            return $horasLaborales;
        }

        // Obtener horas ocupadas
        $placeholders = str_repeat('?,', count($servicios) - 1) . '?';
        $query = "SELECT hora FROM " . $this->table . " 
                  WHERE id_servicio IN ($placeholders) 
                  AND fecha = ? 
                  AND estado_reserva NOT IN ('Cancelada')";
        
        $stmt = $this->conn->prepare($query);
        $params = array_merge($servicios, [$fecha]);
        $stmt->execute($params);
        
        $horasOcupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Convertir formato de tiempo
        $horasOcupadas = array_map(function($hora) {
            return date('H:i', strtotime($hora));
        }, $horasOcupadas);
        
        $horasDisponibles = array_diff($horasLaborales, $horasOcupadas);
        return array_values($horasDisponibles);
    }
    public function obtenerProximaCita($usuario_id) {
        $query = "SELECT r.*, s.nombre_servicio, s.precio, u.nombre as barbero_nombre
                FROM reserva r
                INNER JOIN servicio s ON r.id_servicio = s.id_servicio
                INNER JOIN usuario u ON s.id_barbero = u.id_usuario
                WHERE r.id_cliente = :usuario_id
                AND r.fecha >= CURDATE()
                AND r.estado_reserva != 'Cancelada'
                ORDER BY r.fecha ASC, r.hora ASC
                LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerReservasUsuario($usuario_id) {
        $query = "SELECT r.*, s.nombre_servicio, s.precio, s.duracion,
                u.nombre as barbero_nombre
                FROM reserva r
                INNER JOIN servicio s ON r.id_servicio = s.id_servicio
                INNER JOIN usuario u ON s.id_barbero = u.id_usuario
                WHERE r.id_cliente = :usuario_id
                ORDER BY r.fecha DESC, r.hora DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cancelarReserva($reserva_id, $usuario_id) {
        $query = "UPDATE reserva 
                SET estado_reserva = 'Cancelada' 
                WHERE id_reserva = :reserva_id 
                AND id_cliente = :usuario_id
                AND fecha >= CURDATE()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':reserva_id', $reserva_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        
        return $stmt->execute();
    }

    // Obtener próximas N citas del usuario
    public function obtenerProximasCitas($usuario_id, $limite = 3) {
        $query = "SELECT r.*, s.nombre_servicio, s.precio, u.nombre as barbero_nombre
                FROM reserva r
                INNER JOIN servicio s ON r.id_servicio = s.id_servicio
                INNER JOIN usuario u ON s.id_barbero = u.id_usuario
                WHERE r.id_cliente = :usuario_id
                AND r.fecha >= CURDATE()
                AND r.estado_reserva != 'Cancelada'
                ORDER BY r.fecha ASC, r.hora ASC
                LIMIT :limite";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>