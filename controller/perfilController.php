<?php 
require_once 'model/usuarioModel.php';
require_once 'model/reservaModel.php';

class perfilController {
    private $usuarioModel;
    private $reservaModel;

    public function __construct() {
        $this->usuarioModel = new usuarioModel();
        $this->reservaModel = new reservaModel();
    }

    public function mostrarPerfil() {
        // Verifica que el usuario haya iniciado sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }

        // Datos del usuario y sus próximas citas (máx. 3)
        $usuario = $this->usuarioModel->obtenerPorId($_SESSION['usuario_id']);
        $proximasCitas = $this->reservaModel->obtenerProximasCitas($_SESSION['usuario_id'], 3);
        
        include 'view/perfil.php';
    }

    public function actualizarPerfil() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=mostrarPerfil');
            exit;
        }

        $datos = [
            'id' => $_SESSION['usuario_id'],
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'password_actual' => $_POST['password_actual'] ?? '',
            'password_nueva' => $_POST['password_nueva'] ?? ''
        ];

        $errores = [];

        // Validaciones básicas
        if (empty($datos['nombre']) || strlen($datos['nombre']) < 2) {
            $errores['nombre'] = 'El nombre debe tener al menos 2 caracteres';
        }

        if (empty($datos['email']) || !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'Email no válido';
        }

        // Verifica si el email ya está usado por otro usuario
        if ($this->usuarioModel->emailExisteOtroUsuario($datos['email'], $datos['id'])) {
            $errores['email'] = 'Este email ya está registrado';
        }

        // Si el usuario quiere cambiar su contraseña
        if (!empty($datos['password_nueva'])) {
            if (strlen($datos['password_nueva']) < 6) {
                $errores['password_nueva'] = 'La nueva contraseña debe tener al menos 6 caracteres';
            }
            if (empty($datos['password_actual'])) {
                $errores['password_actual'] = 'Debes ingresar tu contraseña actual';
            }
        }

        // Si hay errores, recarga el perfil con la info actual
        if (!empty($errores)) {
            $usuario = $this->usuarioModel->obtenerPorId($_SESSION['usuario_id']);
            $proximasCitas = $this->reservaModel->obtenerProximasCitas($_SESSION['usuario_id'], 3);
            include 'view/perfil.php';
            return;
        }

        // Intenta actualizar el perfil
        $resultado = $this->usuarioModel->actualizarPerfil($datos);

        if ($resultado['success']) {
            // Actualiza los datos en la sesión
            $_SESSION['nombre'] = $datos['nombre'];
            $_SESSION['email'] = $datos['email'];
            
            $_SESSION['mensaje_exito'] = 'Perfil actualizado exitosamente';
            header('Location: index.php?action=mostrarPerfil');
            exit;
        } else {
            $error = $resultado['mensaje'];
            $usuario = $this->usuarioModel->obtenerPorId($_SESSION['usuario_id']);
            $proximasCitas = $this->reservaModel->obtenerProximasCitas($_SESSION['usuario_id'], 3);
            include 'view/perfil.php';
        }
    }

    public function reprogramarCita() {
        // Requiere sesión activa
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }

        $reserva_id = $_GET['id'] ?? 0;

        // Redirige al formulario de reserva con la cita a reprogramar
        header('Location: index.php?action=mostrarReserva&reprogramar=' . $reserva_id);
        exit;
    }
}
?>
