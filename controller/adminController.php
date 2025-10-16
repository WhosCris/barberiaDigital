<?php
require_once 'model/Factories/usuarioFactory.php';
require_once 'config/database.php';

class adminController {
    private $usuarioFactory;

    public function __construct() {
        $db = new Database();
        $this->usuarioFactory = new usuarioFactory($db->getConnection());
    }

    // Muestra la vista de login del administrador
    public function mostrarLoginAdmin() {
        include "view/adminLogin.php";
    }

    // Valida las credenciales del administrador
    public function procesarLoginAdmin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=mostrarLoginAdmin');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        try {
            $adminObjeto = $this->usuarioFactory->obtenerUsuarioPorEmailYRol($email, 'admin');

            if ($adminObjeto && password_verify($password, $adminObjeto->getPassword())) {
                $adminObjeto->login();
                $_SESSION['tipo_usuario'] = 1; // Identifica al usuario como admin
                header('Location: index.php?action=adminDashboard');
                exit;
            } else {
                $error = "Email o contraseña incorrectos";
                include 'view/adminLogin.php';
            }
        } catch (Exception $e) {
            $error = "Error en el sistema: " . $e->getMessage();
            include 'view/adminLogin.php';
        }
    }

    // Cierra la sesión del administrador
    public function logoutAdmin() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php?action=mostrarLoginAdmin');
        exit;
    }
}
?>
