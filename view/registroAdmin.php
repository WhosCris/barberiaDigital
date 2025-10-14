<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Administrador - Barbería Elegante</title>
    <link rel="stylesheet" href="assets/css/registro-admin.css">
</head>
<body>
    <div class="container">
        <!-- Sección izquierda - Branding -->
        <div class="brand-section">
            <div class="brand-content">
                <h1 class="brand-title">Barbería Elegante</h1>
                <p class="brand-subtitle">Tu estilo, nuestra pasión</p>
            </div>
        </div>

        <!-- Sección derecha - Formulario -->
        <div class="form-section">
            <div class="form-container">
                <h1>Hola, <?php echo htmlspecialchars(explode(' ', $_SESSION['nombre'])[0]); ?></h1>
                <p class="subtitle">Completa los siguientes campos para registrar un nuevo admin</p>

                <!-- Mensajes -->
                <?php if(isset($_SESSION['mensaje_exito'])): ?>
                    <div class="success-message">
                        <?php 
                        echo $_SESSION['mensaje_exito']; 
                        unset($_SESSION['mensaje_exito']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($error)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <!-- Formulario -->
                <form id="registroAdminForm" method="POST" action="index.php?action=procesarRegistroAdmin">
                    <div class="form-group">
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre" 
                            placeholder="Nombre completo"
                            value="<?php echo isset($datos['nombre']) ? htmlspecialchars($datos['nombre']) : ''; ?>"
                            required
                        >
                        <?php if(isset($errores['nombre'])): ?>
                            <div class="error-text"><?php echo $errores['nombre']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Correo electrónico"
                            value="<?php echo isset($datos['email']) ? htmlspecialchars($datos['email']) : ''; ?>"
                            required
                        >
                        <?php if(isset($errores['email'])): ?>
                            <div class="error-text"><?php echo $errores['email']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <input 
                            type="tel" 
                            id="telefono" 
                            name="telefono" 
                            placeholder="Teléfono"
                            value="<?php echo isset($datos['telefono']) ? htmlspecialchars($datos['telefono']) : ''; ?>"
                            required
                        >
                        <?php if(isset($errores['telefono'])): ?>
                            <div class="error-text"><?php echo $errores['telefono']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Contraseña"
                            required
                        >
                        <?php if(isset($errores['password'])): ?>
                            <div class="error-text"><?php echo $errores['password']; ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn-submit">Registrar</button>
                </form>

                <div class="footer-links">
                    <a href="index.php" class="link-volver">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/registro-admin.js"></script>
</body>
</html>