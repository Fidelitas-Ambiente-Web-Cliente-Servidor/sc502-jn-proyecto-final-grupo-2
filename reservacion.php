<?php
require_once 'php/sesion.php';
require_once 'php/conexion.php';

// Si no está logueado, redirigir al login con mensaje
if (!estaLogueado()) {
    $_SESSION['login_error'] = 'Debes iniciar sesión para hacer una reserva.';
    header('Location: login.php');
    exit;
}

// Cargar habitaciones disponibles
$consulta = $conexion->query("SELECT * FROM habitaciones WHERE estado = 'disponible' ORDER BY precio_noche ASC");
$habitaciones = $consulta->fetchAll();

$errores = $_SESSION['reserva_errores'] ?? [];
unset($_SESSION['reserva_errores']);

$hab_preseleccionada = intval($_GET['habitacion_id'] ?? 0);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar - Hotel Resort Aurora</title>
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
            <a href="reservacion.php" class="activo btn-reservar">Reservar</a>
            <a href="mis_reservas.php">Mis Reservas</a>
            <?php if (esAdmin()): ?><a href="admin/dashboard.php">Admin</a><?php endif; ?>
            <a href="php/logout.php" class="btn-sesion">Salir</a>
        </nav>
    </div>
</header>

<main>
    <div class="form-card" style="max-width: 620px;">
        <h2>Nueva Reservación</h2>
        <p class="subtitulo">Completá el formulario para confirmar tu estadía</p>

        <?php if (!empty($errores)): ?>
        <div class="alerta alerta-error">
            <div>
                <strong>Por favor corregí los siguientes errores:</strong>
                <ul style="margin-top: 0.4rem; padding-left: 1.2rem;">
                    <?php foreach ($errores as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <form action="php/reserva_handler.php" method="POST" id="formReserva">

            <fieldset>
                <legend>Datos de la reserva</legend>

                <div class="campo">
                    <label for="habitacion_id">Habitación</label>
                    <select name="habitacion_id" id="habitacion_id" required>
                        <option value="">Seleccioná una habitación...</option>
                        <?php foreach ($habitaciones as $h): ?>
                        <option value="<?= $h['id'] ?>"
                            data-precio="<?= $h['precio_noche'] ?>"
                            data-capacidad="<?= $h['capacidad'] ?>"
                            <?= $hab_preseleccionada === $h['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($h['codigo']) ?> — <?= htmlspecialchars($h['tipo']) ?> | ₡<?= number_format($h['precio_noche'], 0, ',', '.') ?>/noche | Cap. <?= $h['capacidad'] ?> personas
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="campo">
                        <label for="fecha_entrada">Fecha de entrada</label>
                        <input type="date" name="fecha_entrada" id="fecha_entrada" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="campo">
                        <label for="fecha_salida">Fecha de salida</label>
                        <input type="date" name="fecha_salida" id="fecha_salida" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                    </div>
                </div>

                <div class="campo">
                    <label for="personas">Cantidad de personas</label>
                    <input type="number" name="personas" id="personas" min="1" max="6" value="2" required>
                </div>
            </fieldset>

            <!-- Preview de precio -->
            <div id="preview-precio" class="alerta alerta-info" style="display:none;">
                🧮 <span id="texto-precio"></span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-oro">Confirmar reserva</button>
                <a href="habitaciones.php" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<footer>
    <strong>Hotel Resort Aurora</strong> &mdash; Un Lugar Mágico &bull; ¡Con la mejor calidad!
</footer>

<script src="js/reservacion.js"></script>
</body>
</html>
