<?php
// Variables opcionales que vienen del dashboard
$barberoSeleccionado = $barberoSeleccionado ?? null;
$horaSeleccionada = $horaSeleccionada ?? null;
$fechaSeleccionada = $fechaSeleccionada ?? date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Cita - Barbería</title>
    <link rel="stylesheet" href="assets/css/reserva.css">
</head>
<body>
    <div class="container">
        <div class="image-section">
            <div class="calendar-container">
                <h3>October 2025</h3>
                <div class="calendar">
                    <div class="calendar-header">
                        <div>S</div><div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div>
                    </div>
                    <div class="calendar-body" id="calendarBody">
                        <!-- Se genera dinámicamente con JS -->
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h1>Book your appointment</h1>

            <?php if(isset($error)): ?>
                <div class="error-message show"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if(isset($success)): ?>
                <div class="success-message show"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form id="reservaForm" method="POST" action="index.php?action=confirmarReserva">
                <!-- Barbero -->
                <div class="form-group">
                    <select name="barbero_id" id="barberoSelect" required>
                        <option value="">Select barber</option>
                        <?php if(isset($barberos) && !empty($barberos)): ?>
                            <?php foreach($barberos as $barbero): ?>
                                <option value="<?php echo $barbero['id']; ?>"
                                    <?php echo ($barberoSeleccionado == $barbero['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($barbero['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if(isset($errores['barbero_id'])): ?>
                        <div class="error-text"><?php echo $errores['barbero_id']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Servicio -->
                <div class="form-group">
                    <select name="servicio_id" id="servicioSelect" required>
                        <option value="">Select service</option>
                        <?php if(isset($servicios) && !empty($servicios)): ?>
                            <?php foreach($servicios as $servicio): ?>
                                <option value="<?php echo $servicio['id']; ?>" data-duracion="<?php echo $servicio['duracion']; ?>">
                                    <?php echo htmlspecialchars($servicio['nombre']); ?> - $<?php echo $servicio['precio']; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if(isset($errores['servicio_id'])): ?>
                        <div class="error-text"><?php echo $errores['servicio_id']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Fecha -->
                <div class="form-group">
                    <input type="date" name="fecha" id="fechaInput" placeholder="Select date" required
                        value="<?php echo htmlspecialchars($fechaSeleccionada); ?>">
                    <?php if(isset($errores['fecha'])): ?>
                        <div class="error-text"><?php echo $errores['fecha']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Hora -->
                <div class="form-group">
                    <select name="hora" id="horaSelect" required>
                        <option value="">Select time</option>
                        <?php if($horaSeleccionada): ?>
                            <option value="<?php echo $horaSeleccionada; ?>" selected>
                                <?php echo $horaSeleccionada; ?>
                            </option>
                        <?php endif; ?>
                    </select>
                    <?php if(isset($errores['hora'])): ?>
                        <div class="error-text"><?php echo $errores['hora']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Nombre cliente -->
                <div class="form-group">
                    <input type="text" name="nombre_cliente" placeholder="Full name" required
                           value="<?php echo isset($datos['nombre_cliente']) ? htmlspecialchars($datos['nombre_cliente']) : ''; ?>">
                    <?php if(isset($errores['nombre_cliente'])): ?>
                        <div class="error-text"><?php echo $errores['nombre_cliente']; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Email cliente -->
                <div class="form-group">
                    <input type="email" name="email_cliente" placeholder="Email" required
                           value="<?php echo isset($datos['email_cliente']) ? htmlspecialchars($datos['email_cliente']) : ''; ?>">
                    <?php if(isset($errores['email_cliente'])): ?>
                        <div class="error-text"><?php echo $errores['email_cliente']; ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="submit-btn">CONFIRM RESERVA</button>
            </form>
        </div>
    </div>

    <script src="assets/js/calendario.js"></script>
</body>
</html>
