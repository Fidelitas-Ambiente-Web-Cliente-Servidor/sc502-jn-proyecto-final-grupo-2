<?php
require_once '../php/sesion.php';
require_once '../php/conexion.php';
requiereAdmin();

// Datos generales
$datos = [
    'totalHabitaciones' => $conexion->query("SELECT COUNT(*) FROM habitaciones")->fetchColumn(),
    'disponibles'       => $conexion->query("SELECT COUNT(*) FROM habitaciones WHERE estado = 'disponible'")->fetchColumn(),
    'totalReservas'     => $conexion->query("SELECT COUNT(*) FROM reservas")->fetchColumn(),
    'pendientes'        => $conexion->query("SELECT COUNT(*) FROM reservas WHERE estado = 'pendiente'")->fetchColumn(),
    'confirmadas'       => $conexion->query("SELECT COUNT(*) FROM reservas WHERE estado = 'confirmada'")->fetchColumn(),
    'clientes'          => $conexion->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente'")->fetchColumn(),
];

// Últimas reservas
$reservasRecientes = $conexion->query("
    SELECT r.*, u.nombre AS cliente, h.tipo, h.codigo
    FROM reservas r
    JOIN usuarios u ON r.usuario_id = u.id
    JOIN habitaciones h ON r.habitacion_id = h.id
    ORDER BY r.fecha_reserva DESC
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Hotel Resort Aurora</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="../index.php" class="logo-wrap">
            <img src="../img/Hotel.jpg" alt="Aurora">
            <div class="logo-texto">Hotel Resort Aurora<span>Panel de Administración</span></div>
        </a>
        <nav>
            <a href="dashboard.php" class="activo">Dashboard</a>
            <a href="habitaciones.php">Habitaciones</a>
            <a href="reservas.php">Reservas</a>
            <a href="../index.php">Ver sitio</a>
            <a href="../php/logout.php" class="btn-sesion">Salir</a>
        </nav>
    </div>
</header>

<main>
    <div class="seccion-titulo">
        <h2>Dashboard</h2>
        <div class="linea-oro"></div>
        <p>Resumen general del Hotel Resort Aurora</p>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-numero"><?= $datos['totalHabitaciones'] ?></div>
            <div class="stat-label">Habitaciones totales</div>
        </div>
        <div class="stat-card" style="border-color: #27ae60;">
            <div class="stat-numero"><?= $datos['disponibles'] ?></div>
            <div class="stat-label">Disponibles</div>
        </div>
        <div class="stat-card" style="border-color: #3b82f6;">
            <div class="stat-numero"><?= $datos['totalReservas'] ?></div>
            <div class="stat-label">Reservas totales</div>
        </div>
        <div class="stat-card" style="border-color: #f59e0b;">
            <div class="stat-numero"><?= $datos['pendientes'] ?></div>
            <div class="stat-label">Pendientes</div>
        </div>
        <div class="stat-card" style="border-color: #27ae60;">
            <div class="stat-numero"><?= $datos['confirmadas'] ?></div>
            <div class="stat-label">Confirmadas</div>
        </div>
        <div class="stat-card" style="border-color: #8b5cf6;">
            <div class="stat-numero"><?= $datos['clientes'] ?></div>
            <div class="stat-label">Clientes registrados</div>
        </div>
    </div>

    <!-- Accesos rápidos -->
    <div class="flex gap-1 mb-3">
        <a href="habitaciones.php" class="btn btn-azul">Gestionar habitaciones</a>
        <a href="reservas.php" class="btn btn-oro">Ver todas las reservas</a>
    </div>

    <!-- Últimas reservas -->
    <div class="seccion-titulo">
        <h2 style="font-size:1.3rem;">Últimas reservas</h2>
    </div>

    <div class="tabla-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Habitación</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reservasRecientes)): ?>
                <tr><td colspan="8" style="text-align:center; color:#999;">No hay reservas aún.</td></tr>
                <?php else: ?>
                <?php foreach ($reservasRecientes as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['cliente']) ?></td>
                    <td><?= htmlspecialchars($r['codigo']) ?> — <?= htmlspecialchars($r['tipo']) ?></td>
                    <td><?= $r['fecha_entrada'] ?></td>
                    <td><?= $r['fecha_salida'] ?></td>
                    <td>₡<?= number_format($r['precio_total'], 0, ',', '.') ?></td>
                    <td><span class="badge badge-<?= $r['estado'] ?>"><?= ucfirst($r['estado']) ?></span></td>
                    <td>
                        <?php if ($r['estado'] === 'pendiente'): ?>
                            <a href="confirmar_reserva.php?id=<?= $r['id'] ?>" class="btn btn-exito">Confirmar</a>
                            <a href="../php/cancelar_reserva.php?id=<?= $r['id'] ?>" class="btn btn-peligro"
                               onclick="return confirm('¿Cancelar esta reserva?')">Cancelar</a>
                        <?php else: ?>
                            <span class="text-muted text-sm">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<footer>
    <strong>Hotel Resort Aurora</strong> &mdash; Panel de Administración
</footer>
</body>
</html>
