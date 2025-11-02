<?php
require_once("funciones/fxAdmin.php");
require_once("masterApp.php");

/* Devuelve la lista de eventos o gestiona su creación/edición/eliminación.*/
function devuelveEventos() {
    if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
        header("Location: index.php");
        exit;
    }
    
    // Agregar / Editar
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $titulo = $_POST["titulo"];
        $descripcion = $_POST["descripcion"];
        $fecha = $_POST["fecha"];
        $cupos = $_POST["cupos"];
        $id_editar = $_POST['id'] ?? null;
        $precio = $_POST['precio'] ?? 0;

        // Imagen
        $imagen = null;
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
            $imagen = time() . "_" . $_FILES["imagen"]["name"];
            move_uploaded_file($_FILES["imagen"]["tmp_name"], "imagenes/" . $imagen);
        }

        if ($id_editar) {
            editarEvento($id_editar, $titulo, $descripcion, $fecha, $cupos, $imagen, $precio);
            header("Location: admin.php?msg=Evento+editado+con+éxito");
        } else {
            agregarEvento($titulo, $descripcion, $fecha, $cupos, $imagen, $precio);
            header("Location: admin.php?msg=Evento+creado+con+éxito");
        }
        exit;
    }

    // Borrar evento
    if (isset($_GET['borrar'])) {
        borrarEvento($_GET['borrar']);
        header("Location: admin.php?msg=Evento+borrado+con+éxito");
        exit;
    }

    // Si se está editando, devolver el evento seleccionado
    if (isset($_GET['editar'])) {
        return obtenerEvento($_GET['editar']);
    }
    return obtenerEventos();
}
?>