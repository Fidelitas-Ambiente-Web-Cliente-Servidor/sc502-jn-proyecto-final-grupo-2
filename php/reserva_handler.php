<?php
require_once 'conexion.php';
require_once 'sesion.php';
requiereLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../reservacion.php');
    exit;
}

$idHabitacion = intval($_POST['habitacion_id'] ?? 0);
$fechaEntrada = $_POST['fecha_entrada'] ?? '';
$fechaSalida  = $_POST['fecha_salida'] ?? '';
$cantidadPersonas = intval($_POST['personas'] ?? 1);
$idUsuario = $_SESSION['usuario_id'];

$errores = [];

// Validaciones básicas
if (!$idHabitacion) $errores[] = 'Seleccione una habitación.';
if (empty($fechaEntrada)) $errores[] = 'La fecha de entrada es obligatoria.';
if (empty($fechaSalida))  $errores[] = 'La fecha de salida es obligatoria.';
if ($fechaEntrada >= $fechaSalida) $errores[] = 'La salida debe ser después de la entrada.';
if ($fechaEntrada < date('Y-m-d')) $errores[] = 'No se puede reservar en el pasado.';

if (empty($errores)) {

    // Buscar habitación
    $consulta = $conexion->prepare("SELECT * FROM habitaciones WHERE id = ? AND estado = 'disponible'");
    $consulta->execute([$idHabitacion]);

    $habitacion = $consulta->fetch();

    if (!$habitacion) {
        $errores[] = 'La habitación no está disponible.';

    } elseif ($cantidadPersonas > $habitacion['capacidad']) {
        $errores[] = "Máximo {$habitacion['capacidad']} personas.";

    } else {

        // Verificar fechas ocupadas
        $consultaFechas = $conexion->prepare("
            SELECT id FROM reservas
            WHERE habitacion_id = ?
              AND estado != 'cancelada'
              AND fecha_entrada < ?
              AND fecha_salida > ?
        ");
        $consultaFechas->execute([$idHabitacion, $fechaSalida, $fechaEntrada]);

        if ($consultaFechas->fetch()) {
            $errores[] = 'La habitación ya está reservada en esas fechas.';
        }
    }
}

// Si hay errores
if (!empty($errores)) {
    $_SESSION['reserva_errores'] = $errores;
    header('Location: ../reservacion.php');
    exit;
}

// Calcular precio
$noches = (strtotime($fechaSalida) - strtotime($fechaEntrada)) / 86400;
$total  = $noches * $habitacion['precio_noche'];

// Guardar reserva
$consulta = $conexion->prepare("
    INSERT INTO reservas (usuario_id, habitacion_id, fecha_entrada, fecha_salida, cantidad_personas, precio_total, estado)
    VALUES (?, ?, ?, ?, ?, ?, 'pendiente')
");

$consulta->execute([$idUsuario, $idHabitacion, $fechaEntrada, $fechaSalida, $cantidadPersonas, $total]);

// Mensaje de éxito
$_SESSION['reserva_exitosa'] = [
    'id'         => $conexion->lastInsertId(),
    'habitacion' => $habitacion['tipo'] . ' (' . $habitacion['codigo'] . ')',
    'entrada'    => $fechaEntrada,
    'salida'     => $fechaSalida,
    'noches'     => $noches,
    'total'      => number_format($total, 0, ',', '.'),
];

header('Location: ../mis_reservas.php');
exit;