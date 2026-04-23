<?php
require_once 'php/sesion.php';
require_once 'php/conexion.php';

$tipoFiltro = $_GET['tipo'] ?? '';

$sql = "SELECT * FROM habitaciones WHERE estado != 'mantenimiento'";
$valores = [];

if ($tipoFiltro) {
    $sql .= " AND tipo = ?";
    $valores[] = $tipoFiltro;
}

$sql .= " ORDER BY precio_noche ASC";

$consulta = $conexion->prepare($sql);
$consulta->execute($valores);

$listaHabitaciones = $consulta->fetchAll();

$iconosServicios = [
    'WiFi' => '📶',
    'Desayuno' => '🍳',
    'Aire acondicionado' => '❄️',
    'TV' => '📺',
    'Jacuzzi' => '🛁',
    'Minibar' => '🍹'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitaciones - Hotel Resort Aurora</title>
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
            <a href="habitaciones.php" class="activo">Habitaciones</a>
            <a href="reservacion.php" class="btn-reservar">Reservar</a>
            <?php if (estaLogueado()): ?>
                <a href="mis_reservas.php">Mis Reservas</a>
                <?php if (esAdmin()): ?><a href="admin/dashboard.php">Admin</a><?php endif; ?>
                <a href="php/logout.php" class="btn-sesion">Salir</a>
            <?php else: ?>
                <a href="login.php" class="btn-sesion">Iniciar sesión</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main>
    <div class="seccion-titulo flex justify-between items-center">
        <div>
            <h2>Nuestras Habitaciones</h2>
            <div class="linea-oro"></div>
            <p>Elegí el espacio perfecto para tu estadía</p>
        </div>
        <!-- Filtros -->
        <div class="flex gap-1">
            <a href="habitaciones.php" class="btn <?= !$tipoFiltro ? 'btn-azul' : 'btn-outline' ?>" style="font-size:0.82rem; padding:0.4rem 0.9rem;">Todas</a>
            <a href="habitaciones.php?tipo=Estándar" class="btn <?= $tipoFiltro==='Estandar' ? 'btn-azul' : 'btn-outline' ?>" style="font-size:0.82rem; padding:0.4rem 0.9rem;">Estandar</a>
            <a href="habitaciones.php?tipo=Deluxe" class="btn <?= $tipoFiltro==='Deluxe' ? 'btn-azul' : 'btn-outline' ?>" style="font-size:0.82rem; padding:0.4rem 0.9rem;">Deluxe</a>
            <a href="habitaciones.php?tipo=Suite" class="btn <?= $tipoFiltro==='Suite' ? 'btn-azul' : 'btn-outline' ?>" style="font-size:0.82rem; padding:0.4rem 0.9rem;">Suite</a>
        </div>
    </div>

    <?php if (empty($listaHabitaciones)): ?>
        <div class="alerta alerta-info">No hay habitaciones disponibles con ese filtro.</div>
    <?php else: ?>
    <div class="cards-grid">
        <?php foreach ($listaHabitaciones as $h): ?>
        <div class="card">
            <img src="img/<?= htmlspecialchars($h['imagen']) ?>" alt="<?= htmlspecialchars($h['tipo']) ?>">
            <div class="card-body">
                <div class="card-tipo"><?= htmlspecialchars($h['codigo']) ?> &bull; <?= htmlspecialchars($h['tipo']) ?></div>
                <h3><?= htmlspecialchars($h['tipo']) ?></h3>
                <p class="text-sm text-muted mb-1">👥 Capacidad: <?= $h['capacidad'] ?> personas</p>
                <div class="card-precio">
                    ₡<?= number_format($h['precio_noche'], 0, ',', '.') ?>
                    <span>/ noche</span>
                </div>
                <div class="tags">
                    <?php foreach (explode(',', $h['servicios']) as $s): ?>
                        <span class="tag"><?= ($iconosServicios[trim($s)] ?? '') . ' ' . htmlspecialchars(trim($s)) ?></span>
                    <?php endforeach; ?>
                </div>
                <span class="badge badge-<?= $h['estado'] ?>"><?= ucfirst($h['estado']) ?></span>
                <div style="margin-top: 1rem;">
                    <?php if ($h['estado'] === 'disponible'): ?>
                        <a href="reservacion.php?habitacion_id=<?= $h['id'] ?>" class="btn btn-oro" style="width:100%; text-align:center;">Reservar</a>
                    <?php else: ?>
                        <span class="btn btn-azul" style="width:100%; text-align:center; opacity:0.6; cursor:not-allowed;">No disponible</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>

<footer>
    <strong>Hotel Resort Aurora</strong> &mdash; Un Lugar Mágico &bull; ¡Con la mejor calidad!
</footer>
</body>
</html>
