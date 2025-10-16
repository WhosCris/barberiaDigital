<?php 
require_once 'model/usuarioModel.php';

class loginController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new usuarioModel();
    }

    public function mostrarFormularioLogin() {
        // Si el usuario ya inició sesión, redirige al dashboard
        if (isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarDashboard');
            exit;
        }
        include 'view/login.php';
    }

    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $errores = [];

        // Validación básica de campos
        if (empty($email)) {
            $errores['email'] = 'El email es obligatorio';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'Email no válido';
        }

        if (empty($password)) {
            $errores['password'] = 'La contraseña es obligatoria';
        }

        // Si hay errores, vuelve al formulario
        if (!empty($errores)) {
            include 'view/login.php';
            return;
        }

        // Autentica al usuario
        $resultado = $this->usuarioModel->autenticar($email, $password);

        if ($resultado['success']) {
            // Guarda la sesión del usuario
            $_SESSION['usuario_id'] = $resultado['usuario']['id_usuario'];
            $_SESSION['nombre'] = $resultado['usuario']['nombre'];
            $_SESSION['email'] = $resultado['usuario']['email'];
            $_SESSION['tipo_usuario'] = $resultado['usuario']['id_tipo_usuario'];

            // Redirige según el tipo de usuario
            if ($resultado['usuario']['id_tipo_usuario'] == 2) {
                header('Location: index.php?action=mostrarDashboard'); // Cliente
            } else {
                header('Location: index.php?action=mostrarDashboard'); // Admin o barbero
            }
            exit;
        } else {
            $error = $resultado['mensaje'];
            include 'view/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?action=mostrarLogin');
        exit;
    }
}
?>
