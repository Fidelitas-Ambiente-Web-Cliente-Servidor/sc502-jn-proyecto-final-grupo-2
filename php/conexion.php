<?php
// php/conexion.php

$servidor = 'db';   // nombre del servicio en docker
$baseDatos = 'hotel_aurora';
$usuario = 'aurora_user';
$clave = 'aurora_pass';

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4", $usuario, $clave);

    // Opciones básicas
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $error) {
    die("Error al conectar con la base de datos");
}