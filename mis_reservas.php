<?php
require_once 'php/sesion.php';
require_once 'php/conexion.php';
requiereLogin();

$usuario_id = $_SESSION['usuario_id'];

$consulta = $conexion->prepare("
    SELECT r.*, h.tipo, h.codigo, h.imagen, h.precio_noche
    FROM reservas r
    JOIN habitaciones h ON r.habitacion_id = h.id
    WHERE r.usuario_id = ?
    ORDER BY r.fecha_reserva DESC
");
$consulta->execute([$usuario_id]);
$reservas = $consulta->fetchAll();

$exitosa = $_SESSION['reserva_exitosa'] ?? null;
unset($_SESSION['reserva_exitosa']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - Hotel Resort Aurora</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo-wrap">
            <img src="img/Hotel.jpg" alt="Aurora">
            <div class="logo-texto">Hotel Resort Aurora<span>Reservaciones en línea</span></div>
        </a>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="habitaciones.php">Habitaciones</a>
            <a href="reservacion.php" class="btn-reservar">Reservar</a>
            <a href="mis_reservas.php" class="activo">Mis Reservas</a>
            <?php if (esAdmin()): ?><a href="admin/dashboard.php">Admin</a><?php endif; ?>
            <a href="php/logout.php" class="btn-sesion">Salir</a>
        </nav>
    </div>
</header>

<main>
    <div class="seccion-titulo flex justify-between items-center">
        <div>
            <h2>Mis Reservas</h2>
            <div class="linea-oro"></div>
            <p>Hola, <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong> — estas son tus reservaciones</p>
        </div>
        <a href="reservacion.php" class="btn btn-oro">+ Nueva reserva</a>
    </div>

    <?php if ($exitosa): ?>
    <div class="alerta alerta-exito">
        ✅ <div>
            <strong>¡Reserva realizada con éxito!</strong><br>
            <?= htmlspecialchars($exitosa['habitacion']) ?> &bull;
            <?= $exitosa['entrada'] ?> → <?= $exitosa['salida'] ?> (<?= $exitosa['noches'] ?> noche<?= $exitosa['noches'] > 1 ? 's' : '' ?>) &bull;
            Total: <strong>₡<?= $exitosa['total'] ?></strong>
        </div>
    </div>
    <?php endif; ?>

    <?php if (empty($reservas)): ?>
        <div class="alerta alerta-info">
            No tenés reservas aún. <a href="reservacion.php" style="color: inherit; font-weight: 600;">¡Hacé tu primera reserva!</a>
        </div>
    <?php else: ?>
    <div class="tabla-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Habitación</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Personas</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['codigo']) ?> — <?= htmlspecialchars($r['tipo']) ?></td>
                    <td><?= $r['fecha_entrada'] ?></td>
                    <td><?= $r['fecha_salida'] ?></td>
                    <td><?= $r['cantidad_personas'] ?></td>
                    <td>₡<?= number_format($r['precio_total'], 0, ',', '.') ?></td>
                    <td><span class="badge badge-<?= $r['estado'] ?>"><?= ucfirst($r['estado']) ?></span></td>
                    <td>
                        <?php if ($r['estado'] === 'pendiente'): ?>
                            <a href="php/cancelar_reserva.php?id=<?= $r['id'] ?>"
                               class="btn btn-peligro"
                               onclick="return confirm('¿Seguro que querés cancelar esta reserva?')">Cancelar</a>
                        <?php else: ?>
                            <span class="text-muted text-sm">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</main>

<footer>
    <strong>Hotel Resort Aurora</strong> &mdash; Un Lugar Mágico &bull; ¡Con la mejor calidad!
</footer>
</body>
</html>
