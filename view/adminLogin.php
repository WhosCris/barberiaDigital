<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Barbería Online</title>
    
    <!-- CSS externo -->
    <link rel="stylesheet" href="assets/css/login.css">

    <!-- Estilos internos específicos para admin -->
    <style>
        /* Badge de administrador encima del formulario */
        .admin-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Título con degradado */
        h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Mensaje de error en login */
        .error-message {
            color: #e74c3c;
            background: #fdecea;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- Sección de imagen decorativa -->
        <div class="image-section"></div>

        <!-- Sección del formulario -->
        <div class="form-section">
            <div class="form-container">

                <!-- Badge de admin -->
                <div class="admin-badge">🔐 Administrador</div>

                <!-- Título principal -->
                <h1>Admin Panel</h1>
                <p class="subtitle">Acceso exclusivo para administradores</p>

                <!-- Mensaje de error dinámico -->
                <?php if(isset($error) && !empty($error)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <!-- Formulario de login de admin -->
                <form method="POST" action="index.php?action=procesarLoginAdmin">

                    <!-- Email de administrador -->
                    <div class="form-group">
                        <label for="email">Email de Administrador</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="admin@barberia.com"
                            required
                            autofocus
                        >
                    </div>

                    <!-- Contraseña -->
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="******"
                            required
                        >
                    </div>

                    <!-- Botón de envío -->
                    <button type="submit" class="submit-btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        INGRESAR AL PANEL
                    </button>
                </form>

                <!-- Footer con link de volver al login normal -->
                <div class="footer-text" style="margin-top: 20px;">
                    <p><a href="index.php?action=mostrarLogin">← Volver al login normal</a></p>
                </div>

            </div>
        </div>

    </div>
</body>
</html>
