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

    // Mostrar perfil del usuario
    public function mostrarPerfil() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }

        // Obtener datos del usuario
        $usuario = $this->usuarioModel->obtenerPorId($_SESSION['usuario_id']);
        
        // Obtener próximas citas (máximo 3)
        $proximasCitas = $this->reservaModel->obtenerProximasCitas($_SESSION['usuario_id'], 3);
        
        include 'view/perfil.php';
    }

    // Actualizar perfil del usuario
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

        // Validar nombre
        if (empty($datos['nombre']) || strlen($datos['nombre']) < 2) {
            $errores['nombre'] = 'El nombre debe tener al menos 2 caracteres';
        }

        // Validar email
        if (empty($datos['email']) || !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'Email no válido';
        }

        // Verificar si el email ya existe (y no es el mismo usuario)
        if ($this->usuarioModel->emailExisteOtroUsuario($datos['email'], $datos['id'])) {
            $errores['email'] = 'Este email ya está registrado';
        }

        // Si quiere cambiar contraseña
        if (!empty($datos['password_nueva'])) {
            if (strlen($datos['password_nueva']) < 6) {
                $errores['password_nueva'] = 'La nueva contraseña debe tener al menos 6 caracteres';
            }
            
            if (empty($datos['password_actual'])) {
                $errores['password_actual'] = 'Debes ingresar tu contraseña actual';
            }
        }

        // Si hay errores, volver al formulario
        if (!empty($errores)) {
            $usuario = $this->usuarioModel->obtenerPorId($_SESSION['usuario_id']);
            $proximasCitas = $this->reservaModel->obtenerProximasCitas($_SESSION['usuario_id'], 3);
            include 'view/perfil.php';
            return;
        }

        // Actualizar perfil
        $resultado = $this->usuarioModel->actualizarPerfil($datos);

        if ($resultado['success']) {
            // Actualizar datos de sesión
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

    // Reprogramar cita
    public function reprogramarCita() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }

        $reserva_id = $_GET['id'] ?? 0;
        
        // Redirigir al formulario de reserva con los datos de la cita actual
        header('Location: index.php?action=mostrarReserva&reprogramar=' . $reserva_id);
        exit;
    }
}
?>