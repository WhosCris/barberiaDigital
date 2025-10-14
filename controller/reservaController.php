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

    public function mostrarFormularioReserva() {
        // 1️⃣ Obtener barberos y servicios para mostrar en el formulario
        $barberos = $this->barberoModel->obtenerBarberos();
        $servicios = $this->barberoModel->obtenerServicios();

        // 2️⃣ Recibir parámetros opcionales desde el dashboard
        $barberoSeleccionado = $_GET['barbero'] ?? null;
        $horaSeleccionada = $_GET['hora'] ?? null;
        $fechaSeleccionada = $_GET['fecha'] ?? date('Y-m-d'); 

        // 3️⃣ Validar barbero
        if ($barberoSeleccionado && !in_array($barberoSeleccionado, array_column($barberos, 'id'))) {
            $barberoSeleccionado = null; // inválido, ignorar
        }

        // 4️⃣ Validar hora
        if ($horaSeleccionada) {
            $horaValida = DateTime::createFromFormat('H:i:s', $horaSeleccionada) 
                         ?: DateTime::createFromFormat('H:i', $horaSeleccionada);
            if (!$horaValida) $horaSeleccionada = null;
        }

        // 5️⃣ Validar fecha
        $fechaValida = DateTime::createFromFormat('Y-m-d', $fechaSeleccionada);
        if (!$fechaValida) $fechaSeleccionada = date('Y-m-d');

        // 6️⃣ Incluir la vista
        include 'view/reserva.php';
    }

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

        if (!empty($errores)) {
            $barberos = $this->barberoModel->obtenerBarberos();
            $servicios = $this->barberoModel->obtenerServicios();
            include 'view/reserva.php';
            return;
        }

        $resultado = $this->reservaModel->crearReserva($datos);

        $barberos = $this->barberoModel->obtenerBarberos();
        $servicios = $this->barberoModel->obtenerServicios();

        if ($resultado['success']) {
            $success = '¡Reserva confirmada exitosamente!';
            $datos = [];
        } else {
            $error = $resultado['mensaje'];
        }

        include 'view/reserva.php';
    }

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
