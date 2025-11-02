<?php
require_once("fxGeneral.php");

function fxObtenerReservasPorUsuario($usuarioId) {
    $conexion = fxAbrirConexion();

    $msDatos = $conexion->prepare("SELECT r.RESERVAS_REL, e.TITULO_002,  e.FECHA_002,  r.CANTIDAD, r.TOTAL_PAGO,r.FECHA_RESERVA
        FROM RESER003 r INNER JOIN RESER002 e ON r.EVENTOS_REL = e.EVENTOS_REL
        WHERE r.USUARIO_REL = ? ORDER BY r.FECHA_RESERVA DESC");
    $msDatos->execute([$usuarioId]);
    return $msDatos->fetchAll(PDO::FETCH_ASSOC);
}

function devuelveReservas($usuarioId) {
    $conexion = fxAbrirConexion();

    $msDatos = $conexion->prepare("
        SELECT 
            r.RESERVAS_REL, 
            e.TITULO_002, 
            e.FECHA_002, 
            r.CANTIDAD, 
            r.TOTAL_PAGO, 
            r.FECHA_RESERVA
        FROM RESER003 r
        INNER JOIN RESER002 e ON r.EVENTOS_REL = e.EVENTOS_REL
        WHERE r.USUARIO_REL = ?
        ORDER BY r.FECHA_RESERVA DESC
    ");
    $msDatos->execute([$usuarioId]);

    return $msDatos->fetchAll(PDO::FETCH_ASSOC);
}
