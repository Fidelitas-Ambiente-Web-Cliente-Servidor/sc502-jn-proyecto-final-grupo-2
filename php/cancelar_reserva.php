<?php
require_once 'conexion.php';
require_once 'sesion.php';
requiereLogin();

$idReserva   = intval($_GET['id'] ?? 0);
$idUsuario   = $_SESSION['usuario_id'];

if ($idReserva) {

    // Si es admin, puede cancelar cualquier reserva
    if (esAdmin()) {
        $consulta = $conexion->prepare("UPDATE reservas SET estado = 'cancelada' WHERE id = ?");
        $consulta->execute([$idReserva]);
        header('Location: ../admin/reservas.php');

    } else {
        // Usuario normal solo puede cancelar sus reservas pendientes
        $consulta = $conexion->prepare("UPDATE reservas SET estado = 'cancelada' WHERE id = ? AND usuario_id = ? AND estado = 'pendiente'");
        $consulta->execute([$idReserva, $idUsuario]);
        header('Location: ../mis_reservas.php');
    }
}

exit;