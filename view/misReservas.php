<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - Barbería Elegante</title>
    <!-- CSS principal del dashboard -->
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <!-- CSS específico para la sección de mis reservas -->
    <link rel="stylesheet" href="assets/css/mis-reservas.css">
</head>
<body>
    <div class="reservas-container">
        
        <!-- Header de la página de reservas -->
        <div class="reservas-header">
            <a href="index.php" class="btn-back">← Volver</a>
            <h1>Mis Reservas</h1>
        </div>

        <!-- Lista de reservas del usuario -->
        <div class="reservas-list">
            <?php if(!empty($reservas)): ?>
                <!-- Iterar sobre cada reserva -->
                <?php foreach($reservas as $reserva): ?>
                    <?php 
                    // Convertir fecha y hora a objeto DateTime
                    $fecha_reserva = new DateTime($reserva['fecha'] . ' ' . $reserva['hora']);
                    $hoy = new DateTime();
                    // Determinar si la reserva ya pasó
                    $es_pasada = $fecha_reserva < $hoy;
                    $estado = $reserva['estado_reserva'];
                    ?>
                    
                    <!-- Tarjeta individual de reserva -->
                    <div class="reserva-card <?php echo $es_pasada ? 'pasada' : ''; ?> estado-<?php echo strtolower($estado); ?>">
                        
                        <!-- Estado de la reserva con ícono -->
                        <div class="reserva-status">
                            <?php
                            $estado_icon = [
                                'Pendiente' => '⏳',
                                'Confirmada' => '✅',
                                'Completada' => '✔️',
                                'Cancelada' => '❌'
                            ];
                            echo $estado_icon[$estado] ?? '📋';
                            ?>
                            <span class="status-text"><?php echo $estado; ?></span>
                        </div>

                        <!-- Detalles de la reserva -->
                        <div class="reserva-details">
                            <div class="reserva-date">
                                <strong>📅 <?php echo $fecha_reserva->format('d/m/Y'); ?></strong>
                                <span class="reserva-time">🕐 <?php echo $fecha_reserva->format('H:i'); ?></span>
                            </div>

                            <div class="reserva-service">
                                <h3><?php echo htmlspecialchars($reserva['nombre_servicio']); ?></h3>
                                <p>Barbero: <strong><?php echo htmlspecialchars($reserva['barbero_nombre']); ?></strong></p>
                                <p>Duración: <?php echo $reserva['duracion']; ?> minutos</p>
                            </div>

                            <div class="reserva-price">
                                $<?php echo number_format($reserva['precio'], 0, ',', '.'); ?>
                            </div>
                        </div>

                        <!-- Botón de cancelar reserva (solo si no es pasada o cancelada) -->
                        <?php if(!$es_pasada && $estado != 'Cancelada'): ?>
                            <div class="reserva-actions">
                                <button onclick="cancelarReserva(<?php echo $reserva['id_reserva']; ?>)" 
                                        class="btn-cancel">
                                    Cancelar Reserva
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Mensaje si no hay reservas -->
                <div class="no-reservas">
                    <p>😊 Aún no tienes reservas</p>
                    <a href="index.php" class="btn btn-primary">Hacer una reserva</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Script para cancelar reserva con confirmación -->
    <script>
        function cancelarReserva(id) {
            if(confirm('¿Estás seguro de que deseas cancelar esta reserva?')) {
                window.location.href = `index.php?action=cancelarReserva&id=${id}`;
            }
        }
    </script>
</body>
</html>
