<?php
require_once 'php/sesion.php';
if (estaLogueado()) { header('Location: index.php'); exit; }

$errores = $_SESSION['registro_errores'] ?? [];
unset($_SESSION['registro_errores']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Hotel Resort Aurora</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body style="background: linear-gradient(135deg, #0D2340 0%, #1a3a5c 100%); min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">

<div class="form-card" style="margin: 2rem auto; width: 100%; max-width: 460px;">
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <img src="img/Hotel.jpg" alt="Hotel Aurora" style="width: 60px; height: 60px; border-radius: 12px; object-fit: cover; border: 2px solid #C9A84C; margin-bottom: 0.75rem;">
        <h2 style="margin-bottom: 0.15rem;">Crear Cuenta</h2>
        <p class="subtitulo">Hotel Resort Aurora</p>
    </div>

    <?php if (!empty($errores)): ?>
    <div class="alerta alerta-error">
        <div>
            <strong>Corregí los siguientes errores:</strong>
            <ul style="margin-top: 0.4rem; padding-left: 1.2rem;">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <form action="php/registro_handler.php" method="POST">
        <div class="campo">
            <label for="nombre">Nombre completo</label>
            <input type="text" name="nombre" id="nombre" placeholder="Juan Pérez" required autofocus>
        </div>
        <div class="campo">
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" placeholder="correo@ejemplo.com" required>
        </div>
        <div class="campo">
            <label for="password">Contraseña <span class="text-muted" style="font-weight:400;">(mínimo 6 caracteres)</span></label>
            <input type="password" name="password" id="password" placeholder="••••••••" required minlength="6">
        </div>
        <div class="campo">
            <label for="confirm_password">Confirmar contraseña</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-oro" style="width: 100%; margin-top: 0.5rem;">Crear cuenta</button>
    </form>

    <p class="text-center text-sm" style="margin-top: 1.2rem; color: #6b6b6b;">
        ¿Ya tenés cuenta? <a href="login.php" style="color: #C9A84C; font-weight: 600; text-decoration: none;">Iniciar sesión</a>
    </p>
    <p class="text-center text-sm" style="margin-top: 0.4rem;">
        <a href="index.php" style="color: #6b6b6b; text-decoration: none;">← Volver al inicio</a>
    </p>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const pass = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    if (pass !== confirm) {
        e.preventDefault();
        alert('Las contraseñas no coinciden.');
    }
});
</script>
</body>
</html>
