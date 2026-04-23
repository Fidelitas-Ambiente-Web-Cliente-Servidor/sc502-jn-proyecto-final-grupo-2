<?php
require_once '../php/sesion.php';
require_once '../php/conexion.php';
requiereAdmin();

// Filtro por estado
$estadoFiltro = $_GET['estado'] ?? '';

$sql = "
    SELECT r.*, u.nombre AS cliente, u.email, h.tipo, h.codigo
    FROM reservas r
    JOIN usuarios u ON r.usuario_id = u.id
    JOIN habitaciones h ON r.habitacion_id = h.id
";

$valores = [];

if ($estadoFiltro) {
    $sql .= " WHERE r.estado = ?";
    $valores[] = $estadoFiltro;
}

$sql .= " ORDER BY r.fecha_reserva DESC";

$consulta = $conexion->prepare($sql);
$consulta->execute($valores);

$listaReservas = $consulta->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas - Admin Hotel Aurora</title>
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
            <a href="dashboard.php">Dashboard</a>
            <a href="habitaciones.php">Habitaciones</a>
            <a href="reservas.php" class="activo">Reservas</a>
            <a href="../index.php">Ver sitio</a>
            <a href="../php/logout.php" class="btn-sesion">Salir</a>
        </nav>
    </div>
</header>

<main>
    <div class="seccion-titulo flex justify-between items-center">
        <div>
            <h2>Gestión de Reservas</h2>
            <div class="linea-oro"></div>
            <p><?= count($listaReservas) ?> reserva<?= count($listaReservas) !== 1 ? 's' : '' ?> encontrada<?= count($listaReservas) !== 1 ? 's' : '' ?></p>
        </div>
        <div class="flex gap-1">
            <a href="reservas.php" class="btn <?= !$filtro_estado ? 'btn-azul' : 'btn-outline' ?>" style="font-size:0.82rem; padding:0.4rem 0.9rem;">Todas</a>
            <a href="reservas.php?estado=pendiente" class="btn <?= $filtro_estado==='pendiente' ? 'btn-azul' : 'btn-outline' ?>" style="font-size:0.82rem; padding:0.4rem 0.9rem;">Pendientes</a>
            <a href="reservas.php?estado=confirmada" class="btn <?= $filtro_estado==='confirmada' ? 'btn-azul' : 'btn-outline' ?>" style="font-size:0.82rem; padding:0.4rem 0.9rem;">Confirmadas</a>
            <a href="reservas.php?estado=cancelada" class="btn <?= $filtro_estado==='cancelada' ? 'btn-azul' : 'btn-outline' ?>" style="font-size:0.82rem; padding:0.4rem 0.9rem;">Canceladas</a>
        </div>
    </div>

    <div class="tabla-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Correo</th>
                    <th>Habitación</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Personas</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($listaReservas)): ?>
                <tr><td colspan="10" style="text-align:center; color:#999; padding: 2rem;">No hay reservas con ese filtro.</td></tr>
                <?php else: ?>
                <?php foreach ($listaReservas as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['cliente']) ?></td>
                    <td style="font-size:0.82rem;"><?= htmlspecialchars($r['email']) ?></td>
                    <td><?= htmlspecialchars($r['codigo']) ?> — <?= htmlspecialchars($r['tipo']) ?></td>
                    <td><?= $r['fecha_entrada'] ?></td>
                    <td><?= $r['fecha_salida'] ?></td>
                    <td><?= $r['cantidad_personas'] ?></td>
                    <td>₡<?= number_format($r['precio_total'], 0, ',', '.') ?></td>
                    <td><span class="badge badge-<?= $r['estado'] ?>"><?= ucfirst($r['estado']) ?></span></td>
                    <td>
                        <?php if ($r['estado'] === 'pendiente'): ?>
                            <a href="confirmar_reserva.php?id=<?= $r['id'] ?>" class="btn btn-exito">✓</a>
                            <a href="../php/cancelar_reserva.php?id=<?= $r['id'] ?>" class="btn btn-peligro"
                               onclick="return confirm('¿Cancelar reserva #<?= $r['id'] ?>?')">✕</a>
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
