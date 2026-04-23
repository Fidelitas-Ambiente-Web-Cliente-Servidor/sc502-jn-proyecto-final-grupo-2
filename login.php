<?php
require_once 'php/sesion.php';
if (estaLogueado()) { header('Location: index.php'); exit; }

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Hotel Resort Aurora</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body style="background: linear-gradient(135deg, #0D2340 0%, #1a3a5c 100%); min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">

<div class="form-card" style="margin: 2rem auto; width: 100%; max-width: 420px;">
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <img src="img/Hotel.jpg" alt="Hotel Aurora" style="width: 60px; height: 60px; border-radius: 12px; object-fit: cover; border: 2px solid #C9A84C; margin-bottom: 0.75rem;">
        <h2 style="margin-bottom: 0.15rem;">Iniciar Sesión</h2>
        <p class="subtitulo">Hotel Resort Aurora</p>
    </div>

    <?php if ($error): ?>
    <div class="alerta alerta-error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="php/login_handler.php" method="POST">
        <div class="campo">
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" placeholder="correo@ejemplo.com" required autofocus>
        </div>
        <div class="campo">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-oro" style="width: 100%; margin-top: 0.5rem;">Entrar</button>
    </form>

    <p class="text-center text-sm" style="margin-top: 1.2rem; color: #6b6b6b;">
        ¿No tenés cuenta? <a href="registro.php" style="color: #C9A84C; font-weight: 600; text-decoration: none;">Registrarse</a>
    </p>
    <p class="text-center text-sm" style="margin-top: 0.4rem;">
        <a href="index.php" style="color: #6b6b6b; text-decoration: none;">← Volver al inicio</a>
    </p>
</div>

</body>
</html>
