<?php
// php/sesion.php - helpers de sesión reutilizables
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estaLogueado(): bool {
    return isset($_SESSION['usuario_id']);
}

function esAdmin(): bool {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function requiereLogin(): void {
    if (!estaLogueado()) {
        header('Location: ../login.php');
        exit;
    }
}

function requiereAdmin(): void {
    if (!esAdmin()) {
        header('Location: ../index.php');
        exit;
    }
}