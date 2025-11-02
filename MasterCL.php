<?php
session_start(); 
require_once("funciones/fxGeneral.php");
$conexion = fxAbrirConexion();

if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
}

$usuario_id = $_SESSION["usuario"];
$rol = $_SESSION["rol"];
$nombre_usuario = "";

$msDatos = $conexion->prepare("SELECT NOMBRE_001 FROM RESER001 WHERE USUARIO_REL = ?");
$msDatos->execute([$usuario_id]);
$nombre_usuario = $msDatos->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Eventos Disponibles</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/estilos.css">
<link rel="stylesheet" href="css/MasterCL.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="usuario.php">🌍 ViaGo</a>
        <div class="ms-auto d-flex gap-2 align-items-center">
            <span class="text-white me-2"><?= $_SESSION["nombre"] ?></span>
            <a href="index.php" class="btn btn-outline-light">Cerrar sesión</a>
        </div>
    </div>
</nav>