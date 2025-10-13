<?php
session_start();
require_once 'controller/loginController.php';
require_once 'controller/registroController.php';
require_once 'controller/reservaController.php';
require_once 'controller/dashboardController.php';

require_once 'controller/adminController.php';
require_once 'controller/perfilController.php';


$action = $_GET['action'] ?? 'mostrarDashboard';

$controller = new adminController();
switch($action) {
    // ===== DASHBOARD PRINCIPAL =====
    case 'mostrarDashboard':
        $controller = new dashboardController();
        $controller->mostrarDashboard();
        break;
    
    case 'mostrarMisReservas':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new dashboardController();
        $controller->mostrarMisReservas();
        break;
    
    case 'cancelarReserva':
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

    case 'mostrarLoginAdmin':
        $controller->mostrarLoginAdmin();
        break;

    case 'procesarLoginAdmin':
        $controller->procesarLoginAdmin();
        break;

    case 'adminDashboard':
        // Validar sesión antes de mostrar dashboard
        if (!isset($_SESSION['logged_in']) || $_SESSION['tipo_usuario'] != 1) {
            header('Location: index.php?action=mostrarLoginAdmin');
            exit;   
        }
        include 'view/adminDashboard.php';
        break;

    case 'logoutAdmin':
        $controller->logoutAdmin();
        break;

    
    // ===== RUTA POR DEFECTO =====
    default:
        $controller = new dashboardController();
        $controller->mostrarDashboard();
        break;

    case 'mostrarPerfil':
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?action=mostrarLogin');
        exit;
    }
    $controller = new perfilController();
    $controller->mostrarPerfil();
    break;

    case 'actualizarPerfil':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new perfilController();
        $controller->actualizarPerfil();
        break;

    case 'reprogramarCita':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=mostrarLogin');
            exit;
        }
        $controller = new perfilController();
        $controller->reprogramarCita();
        break;
}
?>