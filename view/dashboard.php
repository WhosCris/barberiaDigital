<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Barbería Online</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <div class="container">
        <!-- Sección izquierda - Imagen de fondo -->
        <div class="hero-section">
            <div class="hero-overlay">
                <h2 class="hero-title">Barbería Elegante</h2>
                <p class="hero-subtitle">Tu estilo, nuestra pasión</p>
            </div>
        </div>

        <!-- Sección derecha - Contenido -->
        <div class="content-section">
            <!-- Header con saludo y botones -->
            <div class="header">
                <div class="greeting">
                    <?php if(isset($_SESSION['nombre'])): ?>
                        <h1>Hola, <?php echo htmlspecialchars(explode(' ', $_SESSION['nombre'])[0]); ?></h1>
                    <?php else: ?>
                        <h1>Hola, Invitado</h1>
                    <?php endif; ?>
                </div>

                <div class="auth-buttons">
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                        <a href="index.php?action=logout" class="btn btn-logout">Desconectarse</a>
                    <?php else: ?>
                        <a href="index.php?action=mostrarLogin" class="btn btn-secondary">LOGIN</a>
                        <a href="index.php?action=mostrarRegistro" class="btn btn-primary">REGISTRARSE</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Próxima cita -->
            <?php if(isset($_SESSION['usuario_id']) && $proximaCita): ?>
                <div class="next-appointment">
                    <div class="appointment-icon">📅</div>
                    <div class="appointment-details">
                        <h3>Próxima cita:</h3>
                        <p class="appointment-date">
                            <?php 
                            $fecha = new DateTime($proximaCita['fecha']);
                            echo $fecha->format('d \d\e F, H:i'); 
                            ?>
                        </p>
                        <div class="appointment-barber">
                            <span class="barber-icon">👤</span>
                            <span><?php echo htmlspecialchars($proximaCita['barbero_nombre']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulario de reserva rápida -->
            <div class="quick-booking">
                <form action="index.php?action=mostrarReserva" method="GET">
                    <input type="hidden" name="action" value="mostrarReserva">
                    <div class="form-row">
                        <input type="text" name="nombre" placeholder="Full name" required>
                    </div>
                    <div class="form-row">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <?php if(!isset($_SESSION['usuario_id'])): ?>
                        <p class="info-text">* Debes iniciar sesión para continuar</p>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Grid de horarios disponibles -->
            <div class="schedules-grid">
                <?php if(!empty($horariosDisponibles)): ?>
                    <?php foreach(array_slice($horariosDisponibles, 0, 6) as $horario): ?>
                        <?php 
                        $urlReserva = 'index.php?action=mostrarReserva&barbero=' . urlencode($horario['barbero_id']) . 
                                      '&hora=' . urlencode($horario['hora']) . 
                                      '&fecha=' . urlencode($horario['fecha']); 
                        ?>
                        <div class="schedule-card">
                            <div class="schedule-date">
                                <?php 
                                $fecha_hora = new DateTime($horario['fecha'] . ' ' . $horario['hora']);
                                echo $fecha_hora->format('D, d M Y'); 
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
                            <a href="<?php echo $urlReserva; ?>" class="btn-reservar">Reservar</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-schedules">No hay horarios disponibles para hoy</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="assets/js/dashboard.js"></script>
</body>
</html>
