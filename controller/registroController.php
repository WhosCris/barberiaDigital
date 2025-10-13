<?php
require_once 'model/usuarioModel.php';

class registroController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new usuarioModel();
    }

    // Mostrar formulario de registro
    public function mostrarFormularioRegistro() {
        // Si ya está logueado, redirigir a reservas
        if (isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarDashboard');
            exit;
        }
        include 'view/registro.php';
    }

    // Procesar registro
    public function procesarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=mostrarRegistro');
            exit;
        }

        $datos = [
            'nombre' => $_POST['nombre'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? '',
            'telefono' => $_POST['telefono'] ?? ''
        ];

        $errores = [];

        // Validar nombre
        if (empty(trim($datos['nombre']))) {
            $errores['nombre'] = 'El nombre es obligatorio';
        } elseif (strlen(trim($datos['nombre'])) < 2) {
            $errores['nombre'] = 'El nombre debe tener al menos 2 caracteres';
        }

        // Validar email
        if (empty($datos['email'])) {
            $errores['email'] = 'El email es obligatorio';
        } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'Email no válido';
        } elseif ($this->usuarioModel->emailExiste($datos['email'])) {
            $errores['email'] = 'Este email ya está registrado';
        }

        // Validar password
        if (empty($datos['password'])) {
            $errores['password'] = 'La contraseña es obligatoria';
        } elseif (strlen($datos['password']) < 6) {
            $errores['password'] = 'La contraseña debe tener al menos 6 caracteres';
        }

        // Validar fecha de nacimiento
        if (empty($datos['fecha_nacimiento'])) {
            $errores['fecha_nacimiento'] = 'La fecha de nacimiento es obligatoria';
        } else {
            $fecha_nac = new DateTime($datos['fecha_nacimiento']);
            $hoy = new DateTime();
            $edad = $hoy->diff($fecha_nac)->y;
            
            if ($edad < 13) {
                $errores['fecha_nacimiento'] = 'Debes tener al menos 13 años';
            }
        }

        // Si hay errores, volver al formulario
        if (!empty($errores)) {
            include 'view/registro.php';
            return;
        }

        // Registrar usuario
        $resultado = $this->usuarioModel->registrarCliente($datos);

        if ($resultado['success']) {
            // Login automático después del registro
            $_SESSION['usuario_id'] = $resultado['id'];
            $_SESSION['nombre'] = $datos['nombre'];
            $_SESSION['email'] = $datos['email'];
            $_SESSION['tipo_usuario'] = 2; // Cliente

            // Redirigir a reservas
            header('Location: index.php?action=mostrarDashboard');
            exit;
        } else {
            $error = $resultado['mensaje'];
            include 'view/registro.php';
        }
    }
}
?>