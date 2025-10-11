<?php
session_start();
require_once 'controller/loginController.php';
require_once 'controller/registroController.php';
require_once 'controller/reservaController.php';
require_once 'controller/dashboardController.php';

$action = $_GET['action'] ?? 'mostrarDashboard';

switch($action) {
    // === RUTA PRINCIPAL ===
    case 'mostrarDashboard':
        $controller = new dashboardController();
        $controller->mostrarDashboard();
        break;
    
    // === RUTAS DE LOGIN ===
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
    
    // === RUTAS DE REGISTRO ===
    case 'mostrarRegistro':
        $controller = new registroController();
        $controller->mostrarFormularioRegistro();
        break;
    
    case 'procesarRegistro':
        $controller = new registroController();
        $controller->procesarRegistro();
        break;
    
    // === RUTAS DE RESERVA ===
    case 'mostrarReserva':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new reservaController();
        $controller->mostrarFormularioReserva();
        break;
    
    case 'confirmarReserva':
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
    
    default:
        $controller = new dashboardController();
        $controller->mostrarDashboard();
}
?>