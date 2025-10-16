<?php
session_start(); // Iniciar sesión
require_once 'autoload.php'; // Autoload de clases

// Obtener acción desde la URL, por defecto 'mostrarDashboard'
$action = $_GET['action'] ?? 'mostrarDashboard';

// Instancia genérica de adminController (por defecto)
$controller = new adminController();

// Switch principal para manejar todas las rutas
switch($action) {

    // ===== DASHBOARD PRINCIPAL =====
    case 'mostrarDashboard':
        $controller = new dashboardController();
        $controller->mostrarDashboard();
        break;
    
    case 'mostrarMisReservas':
        // Validar que el usuario esté logueado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new dashboardController();
        $controller->mostrarMisReservas();
        break;
    
    case 'cancelarReserva':
        // Validar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new dashboardController();
        $controller->cancelarReserva();
        break;
    
    // ===== AUTENTICACIÓN =====
    case 'mostrarLogin':
        $controller = new loginController();
        $controller->mostrarFormularioLogin();
        break;
    
    case 'procesarLogin':
        $controller = new loginController();
        $controller->procesarLogin();
        break;
    
    case 'logout':
        $controller = new loginController();
        $controller->logout();
        break;
    
    // ===== REGISTRO =====
    case 'mostrarRegistro':
        $controller = new registroController();
        $controller->mostrarFormularioRegistro();
        break;
    
    case 'procesarRegistro':
        $controller = new registroController();
        $controller->procesarRegistro();
        break;
    
    // ===== RESERVAS =====
    case 'mostrarReserva':
        // Validar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new reservaController();
        $controller->mostrarFormularioReserva();
        break;
    
    case 'confirmarReserva':
        // Validar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new reservaController();
        $controller->confirmarReserva();
        break;
    
    case 'obtenerHorasDisponibles':
        $controller = new reservaController();
        $controller->obtenerHorasDisponibles();
        break;

    // ===== ADMIN =====
    case 'mostrarLoginAdmin':
        $controller = new adminController();
        $controller->mostrarLoginAdmin();
        break;

    case 'procesarLoginAdmin':
        $controller->procesarLoginAdmin();
        break;

    case 'adminDashboard':
        // Validar sesión y tipo de usuario
        if (!isset($_SESSION['logged_in']) || $_SESSION['tipo_usuario'] != 1) {
            header('Location: index.php?action=mostrarLoginAdmin');
            exit;   
        }
        include 'view/adminDashboard.php';
        break;

    case 'logoutAdmin':
        $controller->logoutAdmin();
        break;

    // ===== PERFIL =====
    case 'mostrarPerfil':
        // Validar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new perfilController();
        $controller->mostrarPerfil();
        break;

    case 'actualizarPerfil':
        // Validar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new perfilController();
        $controller->actualizarPerfil();
        break;

    case 'reprogramarCita':
        // Validar sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new perfilController();
        $controller->reprogramarCita();
        break;

    // ===== ADMIN REGISTRO =====
    case 'mostrarRegistroAdmin':
        // Validar sesión y tipo de usuario
        if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 1) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new registroAdminController();
        $controller->mostrarFormularioRegistroAdmin();
        break;

    case 'procesarRegistroAdmin':
        // Validar sesión y tipo de usuario
        if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 1) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new registroAdminController();
        $controller->procesarRegistroAdmin();
        break;

    // ===== RUTA POR DEFECTO =====
    default:
        $controller = new dashboardController();
        $controller->mostrarDashboard();
        break;
}
?>