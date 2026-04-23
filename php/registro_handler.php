<?php
require_once 'conexion.php';
require_once 'sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../registro.php');
    exit;
}

$nombre  = trim($_POST['nombre'] ?? '');
$correo  = trim($_POST['email'] ?? '');
$clave   = $_POST['password'] ?? '';
$confirmarClave = $_POST['confirm_password'] ?? '';

$errores = [];

// Validaciones
if (empty($nombre))  $errores[] = 'El nombre es obligatorio.';
if (empty($correo))  $errores[] = 'El correo es obligatorio.';
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = 'El correo no es válido.';
if (strlen($clave) < 6) $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
if ($clave !== $confirmarClave) $errores[] = 'Las contraseñas no coinciden.';

// Verificar si ya existe el correo
if (empty($errores)) {
    $consulta = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
    $consulta->execute([$correo]);

    if ($consulta->fetch()) {
        $errores[] = 'Ya existe una cuenta con ese correo.';
    }
}

// Si hay errores
if (!empty($errores)) {
    $_SESSION['registro_errores'] = $errores;
    header('Location: ../registro.php');
    exit;
}

// Guardar usuario
$claveEncriptada = password_hash($clave, PASSWORD_DEFAULT);

$consulta = $conexion->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'cliente')");
$consulta->execute([$nombre, $correo, $claveEncriptada]);

// Guardar sesión automáticamente
$_SESSION['usuario_id'] = $conexion->lastInsertId();
$_SESSION['nombre']     = $nombre;
$_SESSION['email']      = $correo;
$_SESSION['rol']        = 'cliente';

header('Location: ../index.php');
exit;