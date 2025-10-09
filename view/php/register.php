<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Cuenta</title>
    <link rel="stylesheet" href="../styles/register.css">
</head>
<body>
    <div class="container">
        <div class="image-section"></div>
        <div class="form-section">
            <div class="header">
                <h1>Crear Nueva Cuenta</h1>
                <p>¿Ya tienes cuenta? <a href="#" id="loginLink">Iniciar Sesión</a></p>
            </div>

            <div id="successMessage" class="success-message">
                ¡Cuenta creada exitosamente!
            </div>

            <form id="registroForm">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" placeholder="Jjara Martins">
                    <div class="error-message" id="nombreError"></div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="hello@reallygreatsite.com">
                    <div class="error-message" id="emailError"></div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" placeholder="******">
                    <div class="error-message" id="passwordError"></div>
                </div>

                <div class="form-group">
                    <label for="fechaNacimiento">Por favor ingresa fecha de nacimiento</label>
                    <input type="date" id="fechaNacimiento">
                    <div class="error-message" id="fechaError"></div>
                </div>

                <button type="submit" class="submit-btn">Registrarse</button>
            </form>
        </div>
    </div>
</body>
</html>