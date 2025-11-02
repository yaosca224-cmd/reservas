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

// Obtener nombre del usuario
$stmt = $conexion->prepare("SELECT NOMBRE_001 FROM RESER001 WHERE USUARIO_REL = ?");
$stmt->execute([$usuario_id]);
$nombre_usuario = $stmt->fetchColumn();

// Cargar actividades
$actividades = $conexion->query("
    SELECT b.*, u.NOMBRE_001 AS usuario_nombre
    FROM RESER000 b
    LEFT JOIN RESER001 u ON b.USUARIO_000 = u.USUARIO_REL
    ORDER BY b.FECHA_000 DESC
")->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel - <?= $rol == 'admin' ? 'Administrador' : 'Usuario' ?></title>
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
                <?php if($rol == "admin"): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Eventos</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="usuario.php">Eventos</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="logout.php">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenido principal -->
<div class="container py-4">
    <h3 class="mb-4">Bienvenido, <?= htmlspecialchars($nombre_usuario) ?> 🎉</h3>

    <!-- Tabla de actividades-->
    <div class="card shadow">
        <div class="card-body">
            <h5 class="card-title">Actividades recientes</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Tabla</th>
                        <th>Operación</th>
                        <th>Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($actividades as $act): ?>
                        <tr>
                            <td><?= $act["FECHA_000"] ?></td>
                            <td><?= htmlspecialchars($act["usuario_nombre"]) ?></td>
                            <td><?= htmlspecialchars($act["TABLA_000"]) ?></td>
                            <td><?= htmlspecialchars($act["OPERACION_000"]) ?></td>
                            <td><?= htmlspecialchars($act["REGISTRO_000"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(count($actividades) == 0): ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay actividades registradas</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>