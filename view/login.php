<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barbería Online</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="container">
        
        <div class="image-section"></div>

        <!-- Sección del formulario -->
        <div class="form-section">
            <div class="form-container">
                <h1>Login</h1>
                <p class="subtitle">Sign in to continue</p>

                <?php if(isset($error)): ?>
                    <div class="error-message show">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form id="loginForm" method="POST" action="index.php?action=procesarLogin">
                    <div class="form-group">
                        <label for="email">Please enter Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="hello@reallygreatsite.com"
                            value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                            required
                        >
                        <?php if(isset($errores['email'])): ?>
                            <div class="error-text"><?php echo $errores['email']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password">Please enter password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="******"
                            required
                        >
                        <?php if(isset($errores['password'])): ?>
                            <div class="error-text"><?php echo $errores['password']; ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="submit-btn">LOGIN</button>
                </form>

                <div class="footer-text">
                    <p>¿No tienes cuenta? <a href="index.php?action=mostrarRegistro">Regístrate aquí</a></p>
                    
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/login.js"></script>
</body>
</html>