<?php
session_start();
require_once("funciones/fxGeneral.php");
$conexion = fxAbrirConexion();

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != "usuario") {
    echo json_encode(['success'=>false,'message'=>'No está logueado']);
    exit;
}

if(!isset($_POST['evento_id'], $_POST['cantidad'], $_POST['pago'], $_POST['precio'])){
    echo json_encode(['success'=>false,'message'=>'Datos incompletos']);
    exit;
}

$evento_id = intval($_POST['evento_id']);
$cantidad = intval($_POST['cantidad']);
$precio_unitario = floatval($_POST['precio']);
$total = $cantidad * $precio_unitario;
$pago = $_POST['pago'];

// Guardar reserva
$msDatos = $conexion->prepare("INSERT INTO RESER003 (USUARIO_REL, EVENTOS_REL, CANTIDAD, TOTAL_PAGO, PAGO)VALUES (?, ?, ?, ?, ?)");
$msDatos->execute([$_SESSION["usuario"], $evento_id, $cantidad, $total, $pago]);

// Actualizar cupos
$msDatos2 = $conexion->prepare("UPDATE RESER002 SET CUPOS_002 = CUPOS_002 - ? WHERE EVENTOS_REL = ?");
$msDatos2->execute([$cantidad, $evento_id]);

// Traer cupos actualizados
$msDatos3 = $conexion->prepare("SELECT CUPOS_002 FROM RESER002 WHERE EVENTOS_REL = ?");
$msDatos3->execute([$evento_id]);
$cupos_actual = $msDatos3->fetchColumn();

echo json_encode([
    'success'=>true,
    'message'=>'Reserva realizada correctamente',
    'cupos' => $cupos_actual
]);