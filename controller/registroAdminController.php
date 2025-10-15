<?php 
require_once 'model/usuarioModel.php';

class registroAdminController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new usuarioModel();
    }

    // Mostrar formulario de registro de admin
    public function mostrarFormularioRegistroAdmin() {
        // Solo un admin puede registrar otro admin
        if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 1) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        
        include 'view/registro-admin.php';
    }

    // Procesar registro de admin
    public function procesarRegistroAdmin() {
        // Verificar permisos
        if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 1) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=mostrarRegistroAdmin');
            exit;
        }

        $datos = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'password' => $_POST['password'] ?? ''
        ];

        $errores = [];

        // Validar nombre
        if (empty($datos['nombre']) || strlen($datos['nombre']) < 2) {
            $errores['nombre'] = 'El nombre debe tener al menos 2 caracteres';
        }

        // Validar email
        if (empty($datos['email']) || !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'Email no válido';
        } elseif ($this->usuarioModel->emailExiste($datos['email'])) {
            $errores['email'] = 'Este email ya está registrado';
        }

        // Validar teléfono
        if (empty($datos['telefono'])) {
            $errores['telefono'] = 'El teléfono es obligatorio';
        }

        // Validar contraseña
        if (empty($datos['password'])) {
            $errores['password'] = 'La contraseña es obligatoria';
        } elseif (strlen($datos['password']) < 6) {
            $errores['password'] = 'La contraseña debe tener al menos 6 caracteres';
        }

        // Si hay errores, volver al formulario
        if (!empty($errores)) {
            include 'view/registro-admin.php';
            return;
        }

        // Registrar administrador
        $resultado = $this->usuarioModel->registrarAdministrador($datos);

        if ($resultado['success']) {
            $_SESSION['mensaje_exito'] = 'Administrador registrado exitosamente';
            header('Location: index.php?action=mostrarRegistroAdmin');
            exit;
        } else {
            $error = $resultado['mensaje'];
            include 'view/registro-admin.php';
        }
    }
}