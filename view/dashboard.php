<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Barbería Elegante</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <div class="container">
        <!-- Sección izquierda - Hero con perfil de usuario -->
        <div class="hero-section">
            <div class="hero-overlay">
                <!-- Logo o título -->
                <div class="brand">
                    <h2 class="brand-title">Barbería Elegante</h2>
                    <p class="brand-subtitle">Tu estilo, nuestra pasión</p>
                </div>

                <!-- Perfil de usuario (solo si está logueado) -->
                <?php if(isset($_SESSION['usuario_id']) && $usuario): ?>
                    <div class="user-profile-card">
                        <div class="user-avatar">
                            <?php 
                            // Iniciales del nombre
                            $nombres = explode(' ', $usuario['nombre']);
                            $iniciales = '';
                            foreach(array_slice($nombres, 0, 2) as $nombre) {
                                $iniciales .= strtoupper(substr($nombre, 0, 1));
                            }
                            echo $iniciales;
                            ?>
                        </div>
                        <div class="user-info">
                            <h3 class="user-name"><?php echo htmlspecialchars($usuario['nombre']); ?></h3>
                            <p class="user-email"><?php echo htmlspecialchars($usuario['email']); ?></p>
                            <?php if($usuario['telefono']): ?>
                                <p class="user-phone">📱 <?php echo htmlspecialchars($usuario['telefono']); ?></p>
                            <?php endif; ?>
                        </div>
                        <button class="btn-edit-profile" onclick="alert('Función en desarrollo')">
                            ✏️ Editar Perfil
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sección derecha - Contenido principal -->
        <div class="content-section">
            <!-- Header superior -->
            <div class="top-header">
                <div class="greeting">
                    <?php if(isset($_SESSION['nombre'])): ?>
                        <h1>Hola, <?php echo htmlspecialchars(explode(' ', $_SESSION['nombre'])[0]); ?></h1>
                    <?php else: ?>
                        <h1>Bienvenido</h1>
                    <?php endif; ?>
                </div>

                <div class="header-actions">
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                        <!-- Usuario logueado -->
                        <a href="index.php?action=mostrarMisReservas" class="btn btn-outline">
                            📅 Mis Reservas
                        </a>
                        <a href="index.php?action=logout" class="btn btn-logout">
                            Desconectarse
                        </a>
                    <?php else: ?>
                        <!-- Usuario invitado -->
                        <a href="index.php?action=mostrarLogin" class="btn btn-secondary">
                            LOGIN
                        </a>
                        <a href="index.php?action=mostrarRegistro" class="btn btn-primary">
                            REGISTRARSE
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Próxima cita destacada -->
            <?php if(isset($_SESSION['usuario_id']) && $proximaCita): ?>
                <div class="next-appointment-banner">
                    <div class="appointment-icon">📅</div>
                    <div class="appointment-content">
                        <h3>Próxima cita</h3>
                        <p class="appointment-datetime">
                            <?php 
                            $fecha = new DateTime($proximaCita['fecha'] . ' ' . $proximaCita['hora']);
                            setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish');
                            echo strftime('%A, %d de %B - %H:%M', $fecha->getTimestamp()); 
                            ?>
                        </p>
                        <p class="appointment-service">
                            <?php echo htmlspecialchars($proximaCita['nombre_servicio']); ?> con 
                            <strong><?php echo htmlspecialchars($proximaCita['barbero_nombre']); ?></strong>
                        </p>
                    </div>
                    <div class="appointment-price">
                        $<?php echo number_format($proximaCita['precio'], 0, ',', '.'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Título de horarios -->
            <div class="section-header">
                <h2>Horarios Disponibles Hoy</h2>
                <p class="section-subtitle">Selecciona el horario que más te acomode</p>
            </div>

            <!-- Grid de horarios disponibles -->
            <div class="schedules-grid">
                <?php if(!empty($horariosDisponibles)): ?>
                    <?php foreach($horariosDisponibles as $index => $horario): ?>
                        <div class="schedule-card">
                            <div class="schedule-date">
                                <?php 
                                $fecha = new DateTime($horario['fecha']);
                                echo $fecha->format('D, d M Y');
                                ?>
                            </div>
                            <div class="schedule-time">
                                <?php 
                                $hora_inicio = new DateTime($horario['hora']);
                                $hora_fin = clone $hora_inicio;
                                $hora_fin->modify('+30 minutes');
                                echo $hora_inicio->format('H:i') . ' - ' . $hora_fin->format('H:i');
                                ?>
                            </div>
                            <div class="schedule-barber">
                                <?php echo htmlspecialchars($horario['barbero_nombre']); ?>
                            </div>
                            
                            <?php if(isset($_SESSION['usuario_id'])): ?>
                                <a href="index.php?action=mostrarReserva&barbero=<?php echo $horario['barbero_id']; ?>&hora=<?php echo $horario['hora']; ?>&fecha=<?php echo $horario['fecha']; ?>" 
                                   class="btn-reservar">
                                    Reservar
                                </a>
                            <?php else: ?>
                                <a href="index.php?action=mostrarLogin" class="btn-reservar btn-login-first">
                                    Iniciar sesión
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-schedules">
                        <p>😔 No hay horarios disponibles para hoy</p>
                        <p class="no-schedules-sub">Intenta con otra fecha o regresa mañana</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="assets/js/dashboard.js"></script>
</body>
</html>