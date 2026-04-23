<?php
require_once 'conexion.php';
require_once 'sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$correo = trim($_POST['email'] ?? '');
$clave  = $_POST['password'] ?? '';
$mensajeError = '';

if (empty($correo) || empty($clave)) {
    $mensajeError = 'Por favor complete todos los campos.';
} else {

    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
    $consulta->execute([$correo]);

    $usuario = $consulta->fetch();

    if ($usuario && password_verify($clave, $usuario['password'])) {

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre']     = $usuario['nombre'];
        $_SESSION['email']      = $usuario['email'];
        $_SESSION['rol']        = $usuario['rol'];

        if ($usuario['rol'] === 'admin') {
            header('Location: ../admin/dashboard.php');
        } else {
            header('Location: ../index.php');
        }
        exit;

    } else {
        $mensajeError = 'Correo o contraseña incorrectos.';
    }
}

$_SESSION['login_error'] = $mensajeError;
header('Location: ../login.php');
exit;