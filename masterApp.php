<?php
session_start();
require_once("funciones/fxGeneral.php");
$conexion = fxAbrirConexion();

if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
}

$usuario_id = $_SESSION["usuario"];
$rol = strtolower($_SESSION["rol"] ?? "");
$nombre_usuario = "";
$stmt = $conexion->prepare("SELECT NOMBRE_001 FROM RESER001 WHERE USUARIO_REL = ?");
$stmt->execute([$usuario_id]);
$nombre_usuario = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Reservas</title>
    <link rel="stylesheet" href="css/estilos.css">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Sistema de Reservas</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <!-- Enlaces visibles según rol -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= $rol == 'admin' ? 'adminInicio.php' : 'usuarioInicio.php' ?>">Inicio</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $rol == 'admin' ? 'admReservas.php' : 'misReservas.php' ?>">Reservas</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $rol == 'admin' ? 'admin.php' : 'usuario.php' ?>">Eventos</a>
                </li>
                <?php if($rol == 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="UserAdm.php">Crear Usuario</a>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link text-warning" href="index.php">Cerrar sesión</a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    <h3 class="mb-4">Bienvenido, <?= htmlspecialchars($nombre_usuario) ?> 🎉</h3>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>