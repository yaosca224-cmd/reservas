<?php
require_once("masterApp.php");
require_once("funciones/fxGeneral.php");
$conexion = fxAbrirConexion();
$usuarioCreado = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $clave = password_hash($_POST["clave"], PASSWORD_DEFAULT);
    $rol = $_POST["rol"] ?? 'usuario'; // Valor por defecto 'usuario'

    try {
        $msDatos = $conexion->prepare("INSERT INTO RESER001 (NOMBRE_001, CORREO_001, CLAVE_001, ROL_001) VALUES (?, ?, ?, ?)");
        $msDatos->execute([$nombre, $correo, $clave, $rol]);
        $usuarioCreado = true; // Usuario creado exitosamente
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
    <center>
<div class="card shadow p-4" style="width: 25rem;">
    <h3 class="text-center mb-4">🧾 Crear Cuenta</h3>
    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
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