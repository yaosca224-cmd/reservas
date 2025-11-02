<?php
session_start();
require_once("funciones/fxGeneral.php");
$conexion = fxAbrirConexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $clave = password_hash($_POST["clave"], PASSWORD_DEFAULT);

    try {
        $msDatos = $conexion->prepare("INSERT INTO RESER001 (NOMBRE_001, CORREO_001, CLAVE_001) VALUES (?, ?, ?)");
        $msDatos->execute([$nombre, $correo, $clave]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card shadow p-4" style="width: 25rem;">
    <h3 class="text-center mb-4">🧾 Crear Cuenta</h3>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="clave" class="form-control" required>
        </div>
        <button class="btn btn-success w-100">Registrar</button>
        <div class="text-center mt-3">
            <a href="index.php">Volver al login</a>
        </div>
    </form>
</div>
</body>
</html>
