<?php
require_once 'model/barberoModel.php';
require_once 'model/reservaModel.php';

class dashboardController {
    private $barberoModel;
    private $reservaModel;

    public function __construct() {
        $this->barberoModel = new barberoModel();
        $this->reservaModel = new reservaModel();
    }

    // Mostrar dashboard principal
    public function mostrarDashboard() {
        // Obtener barberos para mostrar sus horarios
        $barberos = $this->barberoModel->obtenerBarberos();
        
        // Obtener próxima cita del usuario si está logueado
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
            
            // Tomar solo algunas horas de ejemplo
            foreach (array_slice($horasDisponibles, 0, 6) as $hora) {
                $horarios[] = [
                    'hora' => $hora,
                    'barbero_id' => $barbero['id'],
                    'barbero_nombre' => $barbero['nombre'],
                    'fecha' => $fecha_hoy // ✅ agregamos la fecha aquí
                ];
            }
        }
        
        return $horarios;
    }
}
?>
