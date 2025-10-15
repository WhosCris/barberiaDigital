<?php 
require_once 'model/barberoModel.php';
require_once 'model/reservaModel.php';
require_once 'model/usuarioModel.php';

class dashboardController {
    private $barberoModel;
    private $reservaModel;
    private $usuarioModel;

    public function __construct() {
        $this->barberoModel = new barberoModel();
        $this->reservaModel = new reservaModel();
        $this->usuarioModel = new usuarioModel();
    }

    public function mostrarDashboard() {
        // Obtener datos completos del usuario si está logueado
        $usuario = null;
        if (isset($_SESSION['usuario_id'])) {
            $usuario = $this->usuarioModel->obtenerPorId($_SESSION['usuario_id']);
        }
        
        // Obtener barberos
        $barberos = $this->barberoModel->obtenerBarberos();
        
        // Obtener próxima cita del usuario
        $proximaCita = null;
        if (isset($_SESSION['usuario_id'])) {
            $proximaCita = $this->reservaModel->obtenerProximaCita($_SESSION['usuario_id']);
        }
        
        // Obtener horarios disponibles para hoy
        $horariosDisponibles = $this->obtenerHorariosDelDia();
        
        include 'view/dashboard.php';
    }

    // Obtener horarios del día actual
    private function obtenerHorariosDelDia() {
        $fecha_hoy = date('Y-m-d');
        $horarios = [];
        
        $barberos = $this->barberoModel->obtenerBarberos();
        
        foreach ($barberos as $barbero) {
            $horasDisponibles = $this->reservaModel->obtenerHorasDisponibles($barbero['id'], $fecha_hoy);
            
            foreach (array_slice($horasDisponibles, 0, 8) as $hora) {
                $horarios[] = [
                    'hora' => $hora,
                    'barbero_id' => $barbero['id'],
                    'barbero_nombre' => $barbero['nombre'],
                    'fecha' => $fecha_hoy
                ];
            }
        }
        
        return $horarios;
    }

    // Mostrar mis reservas
    public function mostrarMisReservas() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }

        $reservas = $this->reservaModel->obtenerReservasUsuario($_SESSION['usuario_id']);
        include 'view/misReservas.php';
    }

    // Cancelar una reserva
    public function cancelarReserva() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }

        $reserva_id = $_GET['id'] ?? 0;
        
        if ($reserva_id > 0) {
            $resultado = $this->reservaModel->cancelarReserva($reserva_id, $_SESSION['usuario_id']);
            
            if ($resultado) {
                $_SESSION['mensaje'] = 'Reserva cancelada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al cancelar la reserva';
            }
        } else {
            $_SESSION['error'] = 'ID de reserva inválido';
        }
        
        header('Location: index.php?action=mostrarMisReservas');
        exit;
    }
}
?>