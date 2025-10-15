<?php

require_once 'model/usuarioModel.php';
// Verifica que el archivo existe y la clase está definida
if (!class_exists('usuarioModel')) {
    die('Error: No se encontró la clase usuarioModel. Verifica el archivo usuarioModel.php.');
}

class adminController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new usuarioModel();
    }

    // Mostrar login de admin
    public function mostrarLoginAdmin() {
        include "view/adminLogin.php";
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
            $_SESSION['id_usuario'] = $admin['id_usuario'];
            $_SESSION['nombre'] = $admin['nombre'];
            $_SESSION['apellido'] = $admin['apellido'] ?? '';
            $_SESSION['email'] = $admin['email'];
            $_SESSION['tipo_usuario'] = 1; // admin
            $_SESSION['logged_in'] = true;

            header('Location: index.php?action=adminDashboard');
            exit;
        } else {
            $error = "Email o contraseña incorrectos";
            include 'view/adminLogin.php';
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
