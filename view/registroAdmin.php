<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Barbería Online</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <style>
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
        
        h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-section"></div>

        <div class="form-section">
            <div class="form-container">
                <div class="admin-badge">🔐 Administrador</div>
                <h1>Admin Panel</h1>
                <p class="subtitle">Acceso exclusivo para administradores</p>

                <?php if(isset($error)): ?>
                    <div class="error-message show">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?action=procesarLoginAdmin">
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

                    <button type="submit" class="submit-btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        INGRESAR AL PANEL
                    </button>
                </form>

                <div class="footer-text" style="margin-top: 20px;">
                    <p><a href="index.php?action=mostrarLogin">← Volver al login normal</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>