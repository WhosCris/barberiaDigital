<?php

require_once 'model/usuarioModel.php';

class adminController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new usuarioModel();
    }

    // Mostrar formulario de login de admin
    public function mostrarLoginAdmin() {
        include __DIR__ . '/../view/adminLogin.php';
    }

    // Procesar login de admin
    public function procesarLoginAdmin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=mostrarLoginAdmin');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Traer el admin de la tabla usuarios
        $admin = $this->usuarioModel->obtenerAdminPorEmail($email);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['id_usuario'] = $admin['id_usuario']; // <- corregido
            $_SESSION['nombre'] = $admin['nombre'];
            $_SESSION['apellido'] = $admin['apellido'] ?? '';
            $_SESSION['email'] = $admin['email'];
            $_SESSION['tipo_usuario'] = 1; // admin
            $_SESSION['logged_in'] = true;

            header('Location: index.php?action=adminDashboard');
            exit;
        } else {
            $error = "Email o contraseña incorrectos";
            include __DIR__ . '/../view/adminLogin.php';
        }
    }

    // Logout
    public function logoutAdmin() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php?action=mostrarLoginAdmin');
        exit;
    }
}
?>
