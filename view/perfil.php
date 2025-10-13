<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Barbería Elegante</title>
    <link rel="stylesheet" href="assets/css/perfil.css">
</head>
<body>
    <div class="perfil-container">
        <!-- Header con botón volver -->
        <div class="perfil-header">
            <a href="index.php" class="btn-back">← Volver</a>
        </div>

        <div class="perfil-content">
            <!-- Columna izquierda - Datos del usuario -->
            <div class="perfil-left">
                <!-- Avatar y saludo -->
                <div class="user-card">
                    <div class="user-avatar-large">
                        <?php 
                        $nombres = explode(' ', $usuario['nombre']);
                        $iniciales = '';
                        foreach(array_slice($nombres, 0, 2) as $nombre) {
                            $iniciales .= strtoupper(substr($nombre, 0, 1));
                        }
                        echo $iniciales;
                        ?>
                        <button class="avatar-edit" onclick="alert('Cambiar foto - Función en desarrollo')">
                            📷
                        </button>
                    </div>
                    <h2>Hola, <?php echo htmlspecialchars(explode(' ', $usuario['nombre'])[0]); ?></h2>
                </div>

                <!-- Formulario de edición -->
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

                <form method="POST" action="index.php?action=actualizarPerfil" class="perfil-form">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre" 
                            value="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                            required
                        >
                        <?php if(isset($errores['nombre'])): ?>
                            <span class="error-text"><?php echo $errores['nombre']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?php echo htmlspecialchars($usuario['email']); ?>"
                            required
                        >
                        <?php if(isset($errores['email'])): ?>
                            <span class="error-text"><?php echo $errores['email']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input 
                                type="tel" 
                                id="telefono" 
                                name="telefono" 
                                value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>"
                                placeholder="+56 9 12345678"
                            >
                        </div>

                        <div class="form-group">
                            <label for="password_dummy">Contraseña</label>
                            <input 
                                type="password" 
                                id="password_dummy" 
                                value="••••••••" 
                                disabled
                            >
                        </div>
                    </div>

                    <!-- Sección cambiar contraseña (colapsable) -->
                    <div class="password-section">
                        <button type="button" class="btn-toggle-password" onclick="togglePasswordFields()">
                            Cambiar contraseña
                        </button>
                        
                        <div id="passwordFields" class="password-fields" style="display: none;">
                            <div class="form-group">
                                <label for="password_actual">Contraseña actual</label>
                                <input 
                                    type="password" 
                                    id="password_actual" 
                                    name="password_actual"
                                >
                                <?php if(isset($errores['password_actual'])): ?>
                                    <span class="error-text"><?php echo $errores['password_actual']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="password_nueva">Nueva contraseña</label>
                                <input 
                                    type="password" 
                                    id="password_nueva" 
                                    name="password_nueva"
                                    placeholder="Mínimo 6 caracteres"
                                >
                                <?php if(isset($errores['password_nueva'])): ?>
                                    <span class="error-text"><?php echo $errores['password_nueva']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-save">GUARDAR CAMBIOS</button>
                </form>
            </div>

            <!-- Columna derecha - Próximas citas -->
            <div class="perfil-right">
                <h2>Mis próximas citas</h2>

                <?php if(!empty($proximasCitas)): ?>
                    <div class="citas-list">
                        <?php foreach($proximasCitas as $cita): ?>
                            <?php
                            $fecha_cita = new DateTime($cita['fecha'] . ' ' . $cita['hora']);
                            ?>
                            <div class="cita-card">
                                <div class="cita-date">
                                    <strong><?php echo $fecha_cita->format('d \d\e F, H:i'); ?></strong>
                                </div>
                                <div class="cita-info">
                                    <h3><?php echo htmlspecialchars($cita['barbero_nombre']); ?></h3>
                                    <p><?php echo htmlspecialchars($cita['nombre_servicio']); ?></p>
                                </div>
                                <div class="cita-actions">
                                    <button 
                                        onclick="cancelarCita(<?php echo $cita['id_reserva']; ?>)" 
                                        class="btn-cancel-small">
                                        Cancelar
                                    </button>
                                    <button 
                                        onclick="reprogramarCita(<?php echo $cita['id_reserva']; ?>)" 
                                        class="btn-reprogramar">
                                        Reprogramar
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <a href="index.php?action=mostrarMisReservas" class="btn-ver-todas">
                        Ver todas mis reservas →
                    </a>
                <?php else: ?>
                    <div class="no-citas">
                        <p>😊 No tienes citas próximas</p>
                        <a href="index.php" class="btn-primary">Hacer una reserva</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="assets/js/perfil.js"></script>
</body>
</html>