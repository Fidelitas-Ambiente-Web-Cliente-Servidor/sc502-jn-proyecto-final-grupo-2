<?php
// php/logout.php
require_once 'sesion.php';
session_destroy();
header('Location: ../index.php');
exit;
