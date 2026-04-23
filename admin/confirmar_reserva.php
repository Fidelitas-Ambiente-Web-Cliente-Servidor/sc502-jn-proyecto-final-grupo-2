<?php
require_once '../php/sesion.php';
require_once '../php/conexion.php';
requiereAdmin();

$idReserva = intval($_GET['id'] ?? 0);

if ($idReserva) {
    $consulta = $conexion->prepare("UPDATE reservas SET estado = 'confirmada' WHERE id = ? AND estado = 'pendiente'");
    $consulta->execute([$idReserva]);
}

// Volver a la página anterior
$paginaAnterior = $_SERVER['HTTP_REFERER'] ?? 'reservas.php';
header("Location: $paginaAnterior");
exit;