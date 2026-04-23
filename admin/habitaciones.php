<?php
require_once '../php/sesion.php';
require_once '../php/conexion.php';
requiereAdmin();

$mensaje = '';
$error   = '';

// Manejar acciones del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoAccion = $_POST['accion'] ?? '';

    // Agregar habitación
    if ($tipoAccion === 'agregar') {
        $codigoHabitacion = strtoupper(trim($_POST['codigo'] ?? ''));
        $tipoHabitacion   = $_POST['tipo'] ?? '';
        $capacidad        = intval($_POST['capacidad'] ?? 0);
        $precio           = floatval($_POST['precio'] ?? 0);
        $descripcion      = trim($_POST['descripcion'] ?? '');
        $servicios        = trim($_POST['servicios'] ?? '');
        $imagen           = trim($_POST['imagen'] ?? 'Hotel.jpg');
        $estado           = $_POST['estado'] ?? 'disponible';

        if ($codigoHabitacion && $tipoHabitacion && $capacidad && $precio) {
            try {
                $consulta = $conexion->prepare("INSERT INTO habitaciones (codigo, tipo, capacidad, precio_noche, descripcion, servicios, imagen, estado) VALUES (?,?,?,?,?,?,?,?)");
                $consulta->execute([$codigoHabitacion, $tipoHabitacion, $capacidad, $precio, $descripcion, $servicios, $imagen, $estado]);
                $mensaje = "Habitación $codigoHabitacion agregada correctamente.";
            } catch (PDOException $e) {
                $error = "El código $codigoHabitacion ya existe.";
            }
        } else {
            $error = 'Completá los campos obligatorios.';
        }
    }

    // Cambiar estado
    if ($tipoAccion === 'cambiar_estado') {
        $idHabitacion = intval($_POST['hab_id'] ?? 0);
        $nuevoEstado  = $_POST['nuevo_estado'] ?? '';

        $estadosValidos = ['disponible', 'ocupada', 'mantenimiento'];

        if ($idHabitacion && in_array($nuevoEstado, $estadosValidos)) {
            $consulta = $conexion->prepare("UPDATE habitaciones SET estado = ? WHERE id = ?");
            $consulta->execute([$nuevoEstado, $idHabitacion]);
            $mensaje = 'Estado actualizado.';
        }
    }

    // Eliminar habitación
    if ($tipoAccion === 'eliminar') {
        $idHabitacion = intval($_POST['hab_id'] ?? 0);

        if ($idHabitacion) {
            // Verificar si tiene reservas activas
            $consulta = $conexion->prepare("SELECT COUNT(*) FROM reservas WHERE habitacion_id = ? AND estado IN ('pendiente','confirmada')");
            $consulta->execute([$idHabitacion]);

            if ($consulta->fetchColumn() > 0) {
                $error = 'No se puede eliminar: tiene reservas activas.';
            } else {
                $conexion->prepare("DELETE FROM habitaciones WHERE id = ?")->execute([$idHabitacion]);
                $mensaje = 'Habitación eliminada.';
            }
        }
    }
}

// Obtener lista de habitaciones
$listaHabitaciones = $conexion->query("SELECT * FROM habitaciones ORDER BY codigo ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitaciones - Admin Hotel Aurora</title>
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
            <a href="habitaciones.php" class="activo">Habitaciones</a>
            <a href="reservas.php">Reservas</a>
            <a href="../index.php">Ver sitio</a>
            <a href="../php/logout.php" class="btn-sesion">Salir</a>
        </nav>
    </div>
</header>

<main>
    <div class="seccion-titulo">
        <h2>Gestión de Habitaciones</h2>
        <div class="linea-oro"></div>
    </div>

    <?php if ($mensaje): ?>
    <div class="alerta alerta-exito mb-2">✅ <?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
    <div class="alerta alerta-error mb-2">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Formulario agregar -->
    <div class="form-card" style="max-width: 100%; margin-bottom: 2rem;">
        <h2 style="font-size: 1.2rem; margin-bottom: 1rem;">Agregar Nueva Habitación</h2>
        <form method="POST">
            <input type="hidden" name="accion" value="agregar">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem;">
                <div class="campo">
                    <label>Código *</label>
                    <input type="text" name="codigo" placeholder="H-07" required maxlength="10">
                </div>
                <div class="campo">
                    <label>Tipo *</label>
                    <select name="tipo" required>
                        <option value="">Seleccioná...</option>
                        <option value="Estándar">Estándar</option>
                        <option value="Deluxe">Deluxe</option>
                        <option value="Suite">Suite</option>
                    </select>
                </div>
                <div class="campo">
                    <label>Capacidad *</label>
                    <input type="number" name="capacidad" min="1" max="10" placeholder="2" required>
                </div>
                <div class="campo">
                    <label>Precio/noche (₡) *</label>
                    <input type="number" name="precio" min="0" placeholder="45000" required>
                </div>
                <div class="campo">
                    <label>Imagen</label>
                    <select name="imagen">
                        <option value="Estandar.jpg">Estándar</option>
                        <option value="Deluxe.jpg">Deluxe</option>
                        <option value="Suite.jpg">Suite</option>
                        <option value="Hotel.jpg">Hotel</option>
                    </select>
                </div>
                <div class="campo">
                    <label>Estado</label>
                    <select name="estado">
                        <option value="disponible">Disponible</option>
                        <option value="ocupada">Ocupada</option>
                        <option value="mantenimiento">Mantenimiento</option>
                    </select>
                </div>
            </div>
            <div class="campo">
                <label>Descripción</label>
                <input type="text" name="descripcion" placeholder="Descripción de la habitación...">
            </div>
            <div class="campo">
                <label>Servicios <span class="text-muted">(separados por coma: WiFi,Desayuno,TV)</span></label>
                <input type="text" name="servicios" placeholder="WiFi,Aire acondicionado,TV">
            </div>
            <button type="submit" class="btn btn-oro">+ Agregar habitación</button>
        </form>
    </div>

    <!-- Tabla de habitaciones -->
    <div class="tabla-wrap">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Capacidad</th>
                    <th>Precio/noche</th>
                    <th>Servicios</th>
                    <th>Estado</th>
                    <th>Cambiar estado</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaHabitaciones as $h): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($h['codigo']) ?></strong></td>
                    <td><?= htmlspecialchars($h['tipo']) ?></td>
                    <td><?= $h['capacidad'] ?> pers.</td>
                    <td>₡<?= number_format($h['precio_noche'], 0, ',', '.') ?></td>
                    <td style="font-size:0.8rem;"><?= htmlspecialchars($h['servicios']) ?></td>
                    <td><span class="badge badge-<?= $h['estado'] ?>"><?= ucfirst($h['estado']) ?></span></td>
                    <td>
                        <form method="POST" style="display:flex; gap:0.3rem; align-items:center;">
                            <input type="hidden" name="accion" value="cambiar_estado">
                            <input type="hidden" name="hab_id" value="<?= $h['id'] ?>">
                            <select name="nuevo_estado" style="padding:0.3rem; font-size:0.8rem; border-radius:6px; border: 1.5px solid #ddd;">
                                <option value="disponible" <?= $h['estado']==='disponible'?'selected':'' ?>>Disponible</option>
                                <option value="ocupada"    <?= $h['estado']==='ocupada'?'selected':'' ?>>Ocupada</option>
                                <option value="mantenimiento" <?= $h['estado']==='mantenimiento'?'selected':'' ?>>Mantenimiento</option>
                            </select>
                            <button type="submit" class="btn btn-azul" style="padding:0.3rem 0.7rem; font-size:0.8rem;">✓</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" onsubmit="return confirm('¿Eliminar habitación <?= htmlspecialchars($h['codigo']) ?>?')">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="hab_id" value="<?= $h['id'] ?>">
                            <button type="submit" class="btn btn-peligro">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<footer>
    <strong>Hotel Resort Aurora</strong> &mdash; Panel de Administración
</footer>
</body>
</html>
