<?php
session_start();
require_once("funciones/fxGeneral.php");
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$conexion = fxAbrirConexion();

// Datos por defecto (puedes cambiarlos)
$nombre = "Admin Principal";
$correo = "admin@correo.com";
$rol = "admin";

/** Función para generar contraseña segura */
function generarPassword($longitud = 12) {
    $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789!@#$%?';
    $max = strlen($chars) - 1;
    $pass = '';
    for ($i = 0; $i < $longitud; $i++) {
        $pass .= $chars[random_int(0, $max)];
    }
    return $pass;
}

try {
    // 1) Comprobar si ya existe un admin con ese correo o rol
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM RESER001 WHERE CORREO_001 = ? OR ROL_001 = ?");
    $stmt->execute([$correo, $rol]);
    $existe = (int) $stmt->fetchColumn();

    if ($existe > 0) {
        // Mensaje genérico, sin detalles sensibles
        echo "Ya existe un usuario administrador. Si necesitas restablecer la contraseña, usa el procedimiento de recuperación o edita el usuario desde la base de datos.";
        exit;
    }

    // 2) Generar contraseña temporal y su hash
    $clave_plana = generarPassword(12);
    $clave_hash = password_hash($clave_plana, PASSWORD_DEFAULT);

    // 3) Insertar admin
    $stmt = $conexion->prepare(
        "INSERT INTO RESER001 (NOMBRE_001, CORREO_001, CLAVE_001, ROL_001) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$nombre, $correo, $clave_hash, $rol]);

    // 4) Mostrar datos de acceso (solo ahora). Escapar para evitar XSS.
    echo "Usuario admin creado correctamente. <br>";
    echo "Correo: " . htmlspecialchars($correo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "<br>";
    echo "<strong>Contraseña temporal (muéstrala y cámbiala inmediatamente):</strong> "
         . htmlspecialchars($clave_plana, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "<br>";
    echo "<p>Por seguridad, inicia sesión y cambia esta contraseña inmediatamente.</p>";
    echo "<a href='index.php'>Ir al login</a>";
} catch (PDOException $e) {
    // NO mostrar $e->getMessage() al usuario. Registrar en logs.
    error_log("crear.php - Error al crear admin: " . $e->getMessage());

    // Mensaje seguro al usuario
    echo "Ocurrió un error al intentar crear el administrador. Contacta con el administrador del sistema.";
}
?>
