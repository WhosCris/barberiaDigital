<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | Barbería Digital</title>
    <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
    <div class="container">
        <div class="left">
            <!-- Imagen del poste de barbería -->
            <img src="../../assets/img/barber-pole.jpg" alt="Barbería" />
        </div>
        <div class="right">
            <div class="login-form">
                <h1>Login</h1>
                <p class="subtitle">Sign in to continue</p>

                <form method="post" action="#">
                    <div class="form-group">
                        <label for="email">Please enter Email</label>
                        <input type="email" id="email" name="email" placeholder="hello@reallygreatsite.com" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Please enter password</label>
                        <input type="password" id="password" name="password" placeholder="******" required>
                    </div>

                    <button type="submit" class="login-button">LOGIN</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
