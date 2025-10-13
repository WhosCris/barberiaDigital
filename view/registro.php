<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Barbería Online</title>
    <link rel="stylesheet" href="assets/css/registro.css">
</head>
<body>
    <div class="container">
        <!-- Sección de imagen -->
        <div class="image-section"></div>

        <!-- Sección del formulario -->
        <div class="form-section">
            <div class="form-container">
                <h1>Create new Account</h1>
                <p class="subtitle">Already Registered? <a href="index.php?action=mostrarLogin">Login</a></p>

                <?php if(isset($error)): ?>
                    <div class="error-message show">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form id="registroForm" method="POST" action="index.php?action=procesarRegistro">
                    <div class="form-group">
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre" 
                            placeholder="Jiara Martins"
                            value="<?php echo isset($datos['nombre']) ? htmlspecialchars($datos['nombre']) : ''; ?>"
                            required
                        >
                        <?php if(isset($errores['nombre'])): ?>
                            <div class="error-text"><?php echo $errores['nombre']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="hello@reallygreatsite.com"
                            value="<?php echo isset($datos['email']) ? htmlspecialchars($datos['email']) : ''; ?>"
                            required
                        >
                        <?php if(isset($errores['email'])): ?>
                            <div class="error-text"><?php echo $errores['email']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
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

                    <div class="form-group">
                        <label for="fecha_nacimiento">Please enter date of birth</label>
                        <input 
                            type="date" 
                            id="fecha_nacimiento" 
                            name="fecha_nacimiento" 
                            placeholder="Date of birth"
                            value="<?php echo isset($datos['fecha_nacimiento']) ? htmlspecialchars($datos['fecha_nacimiento']) : ''; ?>"
                            required
                        >
                        <?php if(isset($errores['fecha_nacimiento'])): ?>
                            <div class="error-text"><?php echo $errores['fecha_nacimiento']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <input 
                            type="tel" 
                            id="telefono" 
                            name="telefono" 
                            placeholder="Teléfono (opcional)"
                            value="<?php echo isset($datos['telefono']) ? htmlspecialchars($datos['telefono']) : ''; ?>"
                        >
                    </div>

                    <button type="submit" class="submit-btn">SIGN UP</button>
                </form>
                <br>
                <div class="admin-link">
                    <p class="subtitle">
                        ¿Eres administrador? 
                    <a href="index.php?action=procesarLoginAdmin" class="link-login">Ingresa aquí</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script src="assets/js/registro.js"></script>
</body>
</html>