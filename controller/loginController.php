<?php
require_once 'model/usuarioModel.php';

class loginController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new usuarioModel();
    }

    public function mostrarFormularioLogin() {
        // Si ya está logueado, redirigir a reservas
        if (isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarDashboard');
            exit;
        }
        include 'view/login.php';
    }

    // Procesar login
    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $errores = [];

        // Validar email
        if (empty($email)) {
            $errores['email'] = 'El email es obligatorio';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'Email no válido';
        }

        // Validar password
        if (empty($password)) {
            $errores['password'] = 'La contraseña es obligatoria';
        }

        // Si hay errores, volver al formulario
        if (!empty($errores)) {
            include 'view/login.php';
            return;
        }

        // Intentar autenticar
        $resultado = $this->usuarioModel->autenticar($email, $password);

        if ($resultado['success']) {
            // Guardar datos en sesión
            $_SESSION['usuario_id'] = $resultado['usuario']['id_usuario'];
            $_SESSION['nombre'] = $resultado['usuario']['nombre'];
            $_SESSION['email'] = $resultado['usuario']['email'];
            $_SESSION['tipo_usuario'] = $resultado['usuario']['id_tipo_usuario'];

            // Redirigir según tipo de usuario
            if ($resultado['usuario']['id_tipo_usuario'] == 2) {
                // Cliente - ir a reservas
                header('Location: index.php?action=mostrarDashboard');
            } else {
                // Admin o Barbero - por ahora también a reservas
                header('Location: index.php?action=mostrarDashboard');
            }
            exit;
        } else {
            $error = $resultado['mensaje'];
            include 'view/login.php';
        }
    }

    // Cerrar sesión
    public function logout() {
        session_destroy();
        header('Location: index.php?action=mostrarLogin');
        exit;
    }
}
?>