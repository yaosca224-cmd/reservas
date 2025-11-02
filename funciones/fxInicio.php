<?php
require_once("fxGeneral.php");

/*Obtiene el contenido principal (Título, Misión y Visión)*/
function fxObtenerContenidoInicio() {
    $conexion = fxAbrirConexion();
    $msDatos = $conexion->query("SELECT * FROM RESER010 LIMIT 1");
    return $msDatos->fetch(PDO::FETCH_ASSOC);
}

/* Guarda o actualiza el contenido principal del inicio.*/
function fxGuardarContenidoInicio($titulo, $mision, $vision) {
    $conexion = fxAbrirConexion();
    $msDatos = $conexion->query("SELECT COUNT(*) FROM RESER010");
    if ($msDatos->fetchColumn() > 0) {
        $sql = "UPDATE RESER010 SET TITULO=?, MISION=?, VISION=? WHERE INICIO_REL=1";
        $msDatos = $conexion->prepare($sql);
        $msDatos->execute([$titulo, $mision, $vision]);
    } else {
        $sql = "INSERT INTO RESER010 (TITULO, MISION, VISION) VALUES (?, ?, ?)";
        $msDatos = $conexion->prepare($sql);
        $msDatos->execute([$titulo, $mision, $vision]);
    }
}

/* Obtiene todas las imágenes del slider*/
function fxObtenerSliders() {
    $conexion = fxAbrirConexion();
    $msDatos = $conexion->query("SELECT * FROM RESER011 ORDER BY ORDEN ASC");
    return $msDatos->fetchAll(PDO::FETCH_ASSOC);
}

/* Agrega una nueva imagen al slider*/
function fxAgregarSlider($archivoTmp, $nombreArchivo, $orden = 1) {
    $conexion = fxAbrirConexion();
    $nombreFinal = time() . '_' . basename($nombreArchivo);
    move_uploaded_file($archivoTmp, 'imagenes/' . $nombreFinal);

    $sql = "INSERT INTO RESER011 (IMAGEN, ORDEN) VALUES (?, ?)";
    $msDatos = $conexion->prepare($sql);
    $msDatos->execute([$nombreFinal, $orden]);
}

/*Elimina una imagen del slider (base de datos + archivo)*/
function fxEliminarSlider($id) {
    $conexion = fxAbrirConexion();
    
    $msDatos = $conexion->prepare("SELECT IMAGEN FROM RESER011 WHERE SLIDER_REL = ?");
    $msDatos->execute([$id]);
    $imagen = $msDatos->fetchColumn();
    $msDatos = $conexion->prepare("DELETE FROM RESER011 WHERE SLIDER_REL = ?");
    $msDatos->execute([$id]);
    if ($imagen && file_exists('imagenes/' . $imagen)) {
        unlink('imagenes/' . $imagen);
    }
}
