<?php
session_start();
require_once("funciones/fxGeneral.php");
try {
    $conexionPDO = fxAbrirConexion();
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
