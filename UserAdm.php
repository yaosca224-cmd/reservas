<?php
require_once("masterApp.php");
require_once("funciones/fxGeneral.php");

$conexion = fxAbrirConexion();
$usuarioCreado = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $correo = $_POST["correo"] ?? '';
    $clave_plana = $_POST["clave"] ?? '';
    $rol = $_POST["rol"] ?? 'usuario';

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($correo) || empty($clave_plana)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        $clave = password_hash($clave_plana, PASSWORD_DEFAULT);
        try {
            $msDatos = $conexion->prepare(
                "INSERT INTO RESER001 (NOMBRE_001, CORREO_001, CLAVE_001, ROL_001) VALUES (?, ?, ?, ?)"
            );
            $msDatos->execute([$nombre, $correo, $clave, $rol]);
            $usuarioCreado = true;
        } catch (PDOException $e) {
            // Registrar el error en el log del servidor
            error_log("UserAdm.php - Error al crear usuario: " . $e->getMessage());

            // Mensaje seguro para el usuario
            $error = "Ocurrió un error al crear el usuario. Por favor, verifica los datos e inténtalo nuevamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<center>
<div class="card shadow p-4" style="width: 25rem;">
    <h3 class="text-center mb-4">🧾 Crear Cuenta</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></div>
    <?php endif; ?>

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
        <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="rol" class="form-select" required>
                <option value="usuario" selected>Cliente</option>
                <option value="admin">Administrador</option>
            </select>
        </div>
        <button class="btn btn-success w-100">Registrar</button>
    </form>
</div>
</center>

<?php if ($usuarioCreado): ?>
<script>
Swal.fire({
    title: '¡Usuario creado con éxito!',
    icon: 'success',
    timer: 5000,
    showConfirmButton: false,
    position: 'center'
});
</script>
<?php endif; ?>

</body>
</html>
