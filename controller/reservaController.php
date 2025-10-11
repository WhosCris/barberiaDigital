<?php
require_once 'model/reservaModel.php';
require_once 'model/barberoModel.php';

class reservaController {
    private $reservaModel;
    private $barberoModel;

    public function __construct() {
        $this->reservaModel = new reservaModel();
        $this->barberoModel = new barberoModel();
    }

    // Mostrar formulario de reserva
    public function mostrarFormularioReserva() {
        $barberos = $this->barberoModel->obtenerBarberos();
        $servicios = $this->barberoModel->obtenerServicios();
        include 'view/reserva.php';
    }

    // Procesar confirmación de reserva
    public function confirmarReserva() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }

        $datos = [
            'barbero_id' => $_POST['barbero_id'] ?? '',
            'servicio_id' => $_POST['servicio_id'] ?? '',
            'fecha' => $_POST['fecha'] ?? '',
            'hora' => $_POST['hora'] ?? '',
            'nombre_cliente' => $_POST['nombre_cliente'] ?? '',
            'email_cliente' => $_POST['email_cliente'] ?? ''
        ];

        $errores = [];

        // Validaciones
        $validaciones = [
            'barbero_id' => $this->reservaModel->validarBarbero($datos['barbero_id']),
            'servicio_id' => $this->reservaModel->validarServicio($datos['servicio_id']),
            'fecha' => $this->reservaModel->validarFecha($datos['fecha']),
            'hora' => $this->reservaModel->validarHora($datos['hora']),
            'nombre_cliente' => $this->reservaModel->validarNombre($datos['nombre_cliente']),
            'email_cliente' => $this->reservaModel->validarEmail($datos['email_cliente'])
        ];

        foreach ($validaciones as $campo => $validacion) {
            if (!$validacion['valido']) {
                $errores[$campo] = $validacion['mensaje'];
            }
        }

        // Si hay errores, volver al formulario
        if (!empty($errores)) {
            $barberos = $this->barberoModel->obtenerBarberos();
            $servicios = $this->barberoModel->obtenerServicios();
            include 'view/reserva.php';
            return;
        }

        // Crear reserva
        $resultado = $this->reservaModel->crearReserva($datos);

        if ($resultado['success']) {
            $success = '¡Reserva confirmada exitosamente!';
            $datos = []; // Limpiar datos
            $barberos = $this->barberoModel->obtenerBarberos();
            $servicios = $this->barberoModel->obtenerServicios();
            include 'view/reserva.php';
        } else {
            $error = $resultado['mensaje'];
            $barberos = $this->barberoModel->obtenerBarberos();
            $servicios = $this->barberoModel->obtenerServicios();
            include 'view/reserva.php';
        }
    }

    // API para obtener horas disponibles (AJAX)
    public function obtenerHorasDisponibles() {
        header('Content-Type: application/json');
        
        $barbero_id = $_GET['barbero_id'] ?? '';
        $fecha = $_GET['fecha'] ?? '';
        
        if (empty($barbero_id) || empty($fecha)) {
            echo json_encode(['success' => false, 'mensaje' => 'Parámetros incompletos']);
            exit;
        }
        
        $horas = $this->reservaModel->obtenerHorasDisponibles($barbero_id, $fecha);
        echo json_encode(['success' => true, 'horas' => $horas]);
        exit;
    }
}
?>