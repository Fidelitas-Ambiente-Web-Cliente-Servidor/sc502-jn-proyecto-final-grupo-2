<?php
require_once 'php/sesion.php';

$nombreUsuario = $_SESSION['nombre'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Resort Aurora</title>
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
            <a href="index.php" class="activo">Inicio</a>
            <a href="habitaciones.php">Habitaciones</a>
            <a href="reservacion.php" class="btn-reservar">Reservar</a>
            <?php if ($nombreUsuario): ?>
                <a href="mis_reservas.php">Mis Reservas</a>
                <?php if (esAdmin()): ?>
                    <a href="admin/dashboard.php">Admin</a>
                <?php endif; ?>
                <a href="php/logout.php" class="btn-sesion">Salir</a>
            <?php else: ?>
                <a href="login.php" class="btn-sesion">Iniciar sesión</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main>
    <!-- MAIN (antes HERO) -->
    <div class="main">
        <div class="main-content">
            <?php if ($nombreUsuario): ?>
                <h1>Página principal<br><span><?= htmlspecialchars($nombreUsuario) ?></span></h1>
            <?php else: ?>
                <h1>Página principal</h1>
            <?php endif; ?>
            <p>Bienvenido al sistema de reservas del hotel.</p>
            <div class="flex gap-1">
                <a href="habitaciones.php" class="btn btn-oro">Ver habitaciones</a>
                <a href="reservacion.php" class="btn btn-outline">Reservar</a>
            </div>
        </div>
    </div>

    <!-- SERVICIOS -->
    <div class="seccion-titulo">
        <h2>Servicios</h2>
        <div class="linea-oro"></div>
        <p>Servicios disponibles en el hotel</p>
    </div>

    <div class="cards-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 2.5rem;">
        <div class="card" style="text-align:center; padding: 1.5rem;">
            <div style="font-size: 2rem;">📶</div>
            <h3>WiFi</h3>
            <p class="text-sm text-muted">Internet en habitaciones</p>
        </div>
        <div class="card" style="text-align:center; padding: 1.5rem;">
            <div style="font-size: 2rem;">🍳</div>
            <h3>Desayuno</h3>
            <p class="text-sm text-muted">Incluido en algunas habitaciones</p>
        </div>
        <div class="card" style="text-align:center; padding: 1.5rem;">
            <div style="font-size: 2rem;">🚗</div>
            <h3>Parqueo</h3>
            <p class="text-sm text-muted">Gratis</p>
        </div>
        <div class="card" style="text-align:center; padding: 1.5rem;">
            <div style="font-size: 2rem;">❄️</div>
            <h3>A/C</h3>
            <p class="text-sm text-muted">Aire acondicionado</p>
        </div>
    </div>

    <!-- REGISTRO -->
    <?php if (!$nombreUsuario): ?>
    <div style="background: #0D2340; border-radius: 12px; padding: 2rem; text-align: center; color: white;">
        <h2>¿No tenés cuenta?</h2>
        <p>Registrate para hacer reservas</p>
        <a href="registro.php" class="btn btn-oro">Registrarse</a>
    </div>
    <?php endif; ?>
</main>

<footer>
    <strong>Hotel Resort Aurora</strong>
</footer>

</body>
</html>