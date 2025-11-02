<?php
session_start();
require_once("funciones/fxGeneral.php");

$conexion = fxAbrirConexion();

// Configura los datos del admin
$nombre = "Admin Principal";
$correo = "admin@correo.com";
$clave_plana = "Admin1234"; // Cambia esta contraseña
$rol = "admin";

// Generar hash de la contraseña
$clave_hash = password_hash($clave_plana, PASSWORD_DEFAULT);

try {
    // Insertar admin
    $stmt = $conexion->prepare("INSERT INTO RESER001 (NOMBRE_001, CORREO_001, CLAVE_001, ROL_001) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $correo, $clave_hash, $rol]);

    echo "Usuario admin creado correctamente. <br>";
    echo "Correo: $correo <br>";
    echo "Contraseña: $clave_plana <br>";
    echo "<a href='index.php'>Ir al login</a>";
} catch (PDOException $e) {
    echo "Error al crear admin: " . $e->getMessage();
}
?>
