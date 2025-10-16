<?php
require_once 'config/database.php';

class reservaModel {
    private $conn;
    private $table = 'reserva';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // VALIDACIONES
    public function validarBarbero($barbero_id) {
        return empty($barbero_id) ? ['valido' => false, 'mensaje' => 'Debes seleccionar un barbero'] : ['valido' => true];
    }

    public function validarServicio($servicio_id) {
        return empty($servicio_id) ? ['valido' => false, 'mensaje' => 'Debes seleccionar un servicio'] : ['valido' => true];
    }

    public function validarFecha($fecha) {
        if (empty($fecha)) return ['valido' => false, 'mensaje' => 'Debes seleccionar una fecha'];
        $fechaSeleccionada = new DateTime($fecha);
        $hoy = new DateTime(); $hoy->setTime(0,0,0);
        return $fechaSeleccionada < $hoy ? ['valido' => false, 'mensaje' => 'La fecha no puede ser anterior a hoy'] : ['valido' => true];
    }

    public function validarHora($hora) {
        return empty($hora) ? ['valido' => false, 'mensaje' => 'Debes seleccionar una hora'] : ['valido' => true];
    }

    public function validarNombre($nombre) {
        return (empty(trim($nombre)) || strlen(trim($nombre)) < 2) ? ['valido' => false, 'mensaje' => 'El nombre debe tener al menos 2 caracteres'] : ['valido' => true];
    }

    public function validarEmail($email) {
        return !filter_var($email, FILTER_VALIDATE_EMAIL) ? ['valido' => false, 'mensaje' => 'Por favor ingresa un email válido'] : ['valido' => true];
    }

    // DISPONIBILIDAD
    public function verificarDisponibilidad($servicio_id, $fecha, $hora) {
        $query = "SELECT id_reserva FROM $this->table WHERE id_servicio = :servicio_id AND fecha = :fecha AND hora = :hora AND estado_reserva NOT IN ('Cancelada') LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':servicio_id', $servicio_id);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }

    // CREAR RESERVA
    public function crearReserva($datos) {
        try {
            $this->conn->beginTransaction();

            // Cliente existente o nuevo
            $queryCliente = "SELECT id_usuario FROM usuario WHERE email = :email AND id_tipo_usuario = 2";
            $stmtCliente = $this->conn->prepare($queryCliente);
            $stmtCliente->bindParam(':email', $datos['email_cliente']);
            $stmtCliente->execute();
            $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

            $id_cliente = $cliente ? $cliente['id_usuario'] : $this->crearClienteTemporal($datos);

            if (!$this->verificarDisponibilidad($datos['servicio_id'], $datos['fecha'], $datos['hora'])) {
                $this->conn->rollBack();
                return ['success' => false, 'mensaje' => 'Esta hora ya no está disponible'];
            }

            $query = "INSERT INTO $this->table (fecha, hora, estado_reserva, id_servicio, id_cliente) 
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

    private function crearClienteTemporal($datos) {
        $queryNuevoCliente = "INSERT INTO usuario (nombre, email, password, id_tipo_usuario, estado) VALUES (:nombre, :email, :password, 2, 'activo')";
        $stmtNuevo = $this->conn->prepare($queryNuevoCliente);
        $passwordTemp = bin2hex(random_bytes(4));
        $passwordHash = password_hash($passwordTemp, PASSWORD_DEFAULT);
        $stmtNuevo->bindParam(':nombre', $datos['nombre_cliente']);
        $stmtNuevo->bindParam(':email', $datos['email_cliente']);
        $stmtNuevo->bindParam(':password', $passwordHash);
        $stmtNuevo->execute();
        return $this->conn->lastInsertId();
    }

    public function obtenerHorasDisponibles($barbero_id, $fecha) {
        $horasLaborales = ['09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00'];
        $queryServicios = "SELECT id_servicio FROM servicio WHERE id_barbero = :barbero_id";
        $stmtServ = $this->conn->prepare($queryServicios);
        $stmtServ->bindParam(':barbero_id', $barbero_id);
        $stmtServ->execute();
        $servicios = $stmtServ->fetchAll(PDO::FETCH_COLUMN);
        if (empty($servicios)) return $horasLaborales;

        $placeholders = str_repeat('?,', count($servicios)-1).'?';
        $query = "SELECT hora FROM $this->table WHERE id_servicio IN ($placeholders) AND fecha = ? AND estado_reserva NOT IN ('Cancelada')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array_merge($servicios, [$fecha]));
        $horasOcupadas = array_map(fn($hora) => date('H:i', strtotime($hora)), $stmt->fetchAll(PDO::FETCH_COLUMN));

        return array_values(array_diff($horasLaborales, $horasOcupadas));
    }

    public function cancelarReserva($reserva_id, $usuario_id) {
        $query = "UPDATE $this->table SET estado_reserva = 'Cancelada' WHERE id_reserva = :reserva_id AND id_cliente = :usuario_id AND fecha >= CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':reserva_id', $reserva_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        return $stmt->execute();
    }

    public function obtenerCitasCliente($usuario_id, $limite = null) {
        $query = "SELECT r.*, s.nombre_servicio, s.precio, u.nombre as barbero_nombre
                  FROM $this->table r
                  INNER JOIN servicio s ON r.id_servicio = s.id_servicio
                  INNER JOIN usuario u ON s.id_barbero = u.id_usuario
                  WHERE r.id_cliente = :usuario_id
                  AND r.fecha >= CURDATE()
                  AND r.estado_reserva != 'Cancelada'
                  ORDER BY r.fecha ASC, r.hora ASC";
        if ($limite) $query .= " LIMIT :limite";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        if ($limite) $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
