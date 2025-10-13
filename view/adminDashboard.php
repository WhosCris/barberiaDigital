<?php 
$nombre   = $_SESSION['nombre'] ?? '';
$apellido = $_SESSION['apellido'] ?? '';
$email    = $_SESSION['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Barbería Online</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
    body { display: flex; min-height: 100vh; background: #ecf0f1; }

    /* Sidebar */
    .sidebar {
        width: 220px;
        background: #2c3e50;
        color: white;
        display: flex;
        flex-direction: column;
        padding: 20px;
    }
    .sidebar h2 { margin-bottom: 20px; }
    .sidebar a {
        color: white;
        text-decoration: none;
        margin: 10px 0;
        display: block;
        padding: 10px;
        border-radius: 5px;
    }
    .sidebar a:hover { background: #34495e; }

    /* Área principal */
    .main-area {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Header */
    .header {
        background: #667eea;
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header span { font-weight: bold; }
    .header a { color: white; text-decoration: none; font-weight: bold; }

    /* Contenido principal */
    .main-content {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
    }

    /* Tarjetas */
    .card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0px 3px 6px rgba(0,0,0,0.1);
    }
</style>
</head>
<body>
    <div class="sidebar">
        <h2>Panel Admin</h2>
        <a href="#">Inicio</a>
        <a href="#">Usuarios</a>
        <a href="#">Reservas</a>
        <a href="#">Configuración</a>
    </div>

    <div class="main-area">
        <div class="header">
            <span><?php echo htmlspecialchars($nombre); ?></span>
            <a href="index.php?action=logoutAdmin">Cerrar sesión</a>
        </div>

        <div class="main-content">
            <h1>Bienvenido al Panel de Administración</h1>
            <p>Email: <?php echo htmlspecialchars($email); ?></p>

            <div class="card">
                <h3>Estadísticas</h3>
                <p>Aquí puedes mostrar datos importantes del sistema.</p>
            </div>

            <div class="card">
                <h3>Acciones Rápidas</h3>
                <ul>
                    <li><a href="#">Gestionar usuarios</a></li>
                    <li><a href="#">Ver reservas</a></li>
                    <li><a href="#">Configurar servicios</a></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
