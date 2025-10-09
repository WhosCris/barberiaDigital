<?php
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Barbería Digital</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1>Bienvenido a Barbería Digital</h1>
        <nav>
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Servicios</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php
            echo "<p>¡Gracias por visitarnos!</p>";
        ?>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Barbería Digital. Todos los derechos reservados.</p>
    </footer>
</body>
</html>