<?php 
require_once 'model/usuarioModel.php';

class registroAdminController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new usuarioModel();
    }

    public function mostrarFormularioRegistroAdmin() {
        // Solo un administrador puede registrar a otro
        if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 1) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        
        include 'view/registro-admin.php';
    }

    public function procesarRegistroAdmin() {
        // Verifica permisos de acceso
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

        // Validaciones básicas
        if (empty($datos['nombre']) || strlen($datos['nombre']) < 2) {
            $errores['nombre'] = 'El nombre debe tener al menos 2 caracteres';
        }

        if (empty($datos['email']) || !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'Email no válido';
        } elseif ($this->usuarioModel->emailExiste($datos['email'])) {
            $errores['email'] = 'Este email ya está registrado';
        }
