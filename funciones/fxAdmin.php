<?php
require_once("funciones/fxGeneral.php");
$conexion = fxAbrirConexion();

// Obtener todos los eventos
function obtenerEventos() {
    global $conexion;
    $msDatos = $conexion->query("SELECT * FROM RESER002 ORDER BY EVENTOS_REL DESC");
    return $msDatos->fetchAll(PDO::FETCH_ASSOC);
}

// Agregar nuevo evento
function agregarEvento($titulo, $descripcion, $fecha, $cupos, $imagen = null, $precio = 0) {
    $conexion = fxAbrirConexion();
    $sql = "INSERT INTO RESER002 (TITULO_002, DESC_002, FECHA_002, CUPOS_002, IMAGEN_002, PRECIO_002) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $msDatos = $conexion->prepare($sql);
    $msDatos->execute([$titulo, $descripcion, $fecha, $cupos, $imagen, $precio]);
}

// Obtener evento por ID
function obtenerEvento($id) {
    global $conexion;
    $msDatos = $conexion->prepare("SELECT * FROM RESER002 WHERE EVENTOS_REL=?");
    $msDatos->execute([$id]);
    return $msDatos->fetch(PDO::FETCH_ASSOC);
}

// Editar evento existente
function editarEvento($id, $titulo, $descripcion, $fecha, $cupos, $imagen = null, $precio = 0) {
    $conexion = fxAbrirConexion();
    if ($imagen) {
        $sql = "UPDATE RESER002 
                SET TITULO_002=?, DESC_002=?, FECHA_002=?, CUPOS_002=?, IMAGEN_002=?, PRECIO_002=? 
                WHERE EVENTOS_REL=?";
        $msDatos = $conexion->prepare($sql);
        $msDatos->execute([$titulo, $descripcion, $fecha, $cupos, $imagen, $precio, $id]);
    } else {
        $sql = "UPDATE RESER002 
                SET TITULO_002=?, DESC_002=?, FECHA_002=?, CUPOS_002=?, PRECIO_002=? 
                WHERE EVENTOS_REL=?";
        $msDatos = $conexion->prepare($sql);
        $msDatos->execute([$titulo, $descripcion, $fecha, $cupos, $precio, $id]);
    }
}

// Borrar evento
function borrarEvento($id) {
    global $conexion;

    //  Borrar reservas asociadas al evento
    $conexion->prepare("DELETE FROM RESER003 WHERE EVENTOS_REL=?")->execute([$id]);

    // Borrar imagen (si existe)
    $msDatosImg = $conexion->prepare("SELECT IMAGEN_002 FROM RESER002 WHERE EVENTOS_REL=?");
    $msDatosImg->execute([$id]);
    $img = $msDatosImg->fetchColumn();
    if ($img && file_exists("imagenes/" . $img)) unlink("imagenes/" . $img);

    //Borrar el evento
    $msDatos = $conexion->prepare("DELETE FROM RESER002 WHERE EVENTOS_REL=?");
    $msDatos->execute([$id]);
}


/* FUNCIÓN PRINCIPAL DEL PANEL ADMIN */

function devuelveEventos() {
    require_once("masterApp.php");

    // Solo administrador
    if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
        header("Location: index.php");
        exit;
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $titulo = $_POST["titulo"];
        $descripcion = $_POST["descripcion"];
        $fecha = $_POST["fecha"];
        $cupos = $_POST["cupos"];
        $id_editar = $_POST['id'] ?? null;
        $precio = $_POST['precio'] ?? 0;

        // Subir imagen
        $imagen = null;
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
            $imagen = time() . "_" . $_FILES["imagen"]["name"];
            move_uploaded_file($_FILES["imagen"]["tmp_name"], "imagenes/" . $imagen);
        }

        // Guardar cambios
        if ($id_editar) {
            editarEvento($id_editar, $titulo, $descripcion, $fecha, $cupos, $imagen, $precio);
            header("Location: admin.php?msg=Evento+editado+con+éxito");
        } else {
            agregarEvento($titulo, $descripcion, $fecha, $cupos, $imagen, $precio);
            header("Location: admin.php?msg=Evento+creado+con+éxito");
        }
        exit;
    }

    // Eliminar evento
    if (isset($_GET['borrar'])) {
        borrarEvento($_GET['borrar']);
        header("Location: admin.php?msg=Evento+borrado+con+éxito");
        exit;
    }

    // Editar evento
    if (isset($_GET['editar'])) {
        return obtenerEvento($_GET['editar']);
    }

    // Devolver todos los eventos
    return obtenerEventos();
}
?>