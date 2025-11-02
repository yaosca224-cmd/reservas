<?php
session_start();
require_once("funciones/fxGeneral.php");
$conexion = fxAbrirConexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];

    $stmt = $conexion->prepare("SELECT * FROM RESER001 WHERE CORREO_001 = ?");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        if (password_verify($clave, $usuario["CLAVE_001"])) {
            $_SESSION["usuario"] = $usuario["USUARIO_REL"];
            $_SESSION["rol"] = $usuario["ROL_001"];
            $_SESSION["nombre"] = $usuario["NOMBRE_001"];
            header("Location: " . ($usuario["ROL_001"] == "admin" ? "adminInicio.php" : "inicio.php"));
            exit;
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login - Reservas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/index.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="card">
    <img src="img/logo.png" alt="Logo" class="logo">
    <h3 class="mb-4 text-center">Iniciar Sesión</h3>
    <?php if (isset($error)) echo "<div class='alert alert-danger w-100 text-center'>$error</div>"; ?>
    <form method="POST" class="w-100">
        <div class="input-container">
            <div class="mb-3 text-start">
                <label class="form-label">Correo</label>
                <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Contraseña</label>
                <input type="password" name="clave" class="form-control" required>
            </div>
        </div>
        <button class="btn btn-primary w-100 mb-3">Ingresar</button>
        <div class="text-center">
            <a href="registro.php">Crear cuenta</a>
        </div>
    </form>
</div>
</body>
</html>