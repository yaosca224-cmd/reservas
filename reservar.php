<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

require_once("funciones/fxGeneral.php");
$conexion = fxAbrirConexion();

header('Content-Type: application/json');

$response = ['success'=>false, 'message'=>'Método no permitido'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $usuario_id = $_SESSION['usuario'] ?? null;

    if (!$usuario_id) {
        echo json_encode(['success'=>false,'message'=>'Debes iniciar sesión']);
        exit;
    }

    $evento_id = $_POST['evento_id'] ?? null;
    $pago = $_POST['pago'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $cantidad = intval($_POST['cantidad'] ?? 1);

    if(!$evento_id || !$pago || !$nombre || !$email || $cantidad < 1){
        $response = ['success'=>false,'message'=>'Faltan datos o cantidad inválida.'];
    } else {
        // Obtener datos del evento
        $msDatos = $conexion->prepare("SELECT CUPOS_002, PRECIO_002 FROM RESER002 WHERE EVENTOS_REL=?");
        $msDatos->execute([$evento_id]);
        $evento = $msDatos->fetch(PDO::FETCH_ASSOC);

        if(!$evento){
            $response = ['success'=>false,'message'=>'Evento no encontrado.'];
        } elseif($evento['CUPOS_002'] < $cantidad){
            $response = ['success'=>false,'message'=>'No hay suficientes cupos disponibles.'];
        } else {
            $total_pago = $evento['PRECIO_002'] * $cantidad;

            // Insertar reserva con cantidad y total
            $msDatosInsert = $conexion->prepare("INSERT INTO RESER003 (USUARIO_REL, EVENTOS_REL, PAGO, CANTIDAD, TOTAL_PAGO) VALUES (?, ?, ?, ?, ?) ");
            $msDatosInsert->execute([$usuario_id, $evento_id, $pago, $cantidad, $total_pago]);

            // Restar cupos
            $msDatosUpdate = $conexion->prepare("UPDATE RESER002 SET CUPOS_002 = CUPOS_002 - ? WHERE EVENTOS_REL=?");
            $msDatosUpdate->execute([$cantidad, $evento_id]);

            // Obtener cupos actualizados
            $msDatos = $conexion->prepare("SELECT CUPOS_002 FROM RESER002 WHERE EVENTOS_REL=?");
            $msDatos->execute([$evento_id]);
            $cuposActual = $msDatos->fetchColumn();

            $response = [
                'success' => true,
                'message' => "¡Reserva registrada correctamente! Total a pagar: $" . number_format($total_pago, 2),
                'cupos' => $cuposActual
            ];
        }
    }
}
echo json_encode($response);
exit;