<?php
require_once("funciones/fxAdmin.php");
require_once("masterApp.php");

/* Devuelve la lista de eventos o gestiona su creación/edición/eliminación. */
function devuelveEventos() {
    if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
        header("Location: index.php");
        exit;
    }

    // Agregar / Editar
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $titulo = $_POST["titulo"] ?? '';
        $descripcion = $_POST["descripcion"] ?? '';
        $fecha = $_POST["fecha"] ?? '';
        $cupos = $_POST["cupos"] ?? 0;
        $id_editar = $_POST['id'] ?? null;
        $precio = $_POST['precio'] ?? 0;

        // Imagen segura
        $imagen = null;
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
            $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            $nombreOriginal = $_FILES["imagen"]["name"];
            $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

            if (!in_array($extension, $extensionesPermitidas)) {
                die("Tipo de archivo no permitido.");
            }

            // Nombre único y seguro
            $imagen = time() . "_" . bin2hex(random_bytes(5)) . "." . $extension;
            $rutaDestino = __DIR__ . "/imagenes/" . $imagen;

            if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
                die("Error al guardar la imagen.");
            }
        }

        // Llamadas a funciones de agregar o editar
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

    // Devolver lista de eventos
    return obtenerEventos();
}
?>
