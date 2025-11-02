<?php
require_once("masterApp.php");
require_once("funciones/fxGeneral.php");
require_once("funciones/fxInicio.php");

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'adminInicio':
            fxGuardarContenidoInicio($_POST['titulo'], $_POST['mision'], $_POST['vision']);
            header("Location: adminInicio.php?msg=Guardado");
            exit;

        case 'agregar_slider':
            if (isset($_FILES['imagen'])) {
                fxAgregarSlider($_FILES['imagen']['tmp_name'], $_FILES['imagen']['name'], $_POST['orden'] ?? 1);
                header("Location: adminInicio.php");
                exit;
            }
            break;

        case 'eliminar_slider':
            fxEliminarSlider($_POST['slider_id']);
            header("Location: adminInicio.php");
            exit;
    }
}

$inicio = fxObtenerContenidoInicio();
$sliders = fxObtenerSliders();
?>

<h2 class="mb-4 text-center text-primary">🖥️ Admin - Pantalla Inicio</h2>

<?php if(isset($_GET['msg'])): ?>
<div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>

<!-- Contenido principal -->
<div class="card mb-4 shadow-sm border-primary">
    <div class="card-header bg-primary text-white">Contenido Principal</div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="accion" value="adminInicio">
            <div class="mb-3">
                <label>Título</label>
                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($inicio['TITULO'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label>Misión</label>
                <textarea name="mision" class="form-control"><?= htmlspecialchars($inicio['MISION'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label>Visión</label>
                <textarea name="vision" class="form-control"><?= htmlspecialchars($inicio['VISION'] ?? '') ?></textarea>
            </div>
            <button class="btn btn-success">Guardar</button>
        </form>
    </div>
</div>

<!-- Slider -->
<div class="card mb-2">
    <div class="card-header bg-primary text-white">Slider de Imágenes</div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" class="mb-2">
            <input type="hidden" name="accion" value="agregar_slider">
            <div class="mb-3">
                <label>Imagen</label>
                <input type="file" name="imagen" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Orden</label>
                <input type="number" name="orden" class="form-control" value="1">
            </div>
            <button class="btn btn-success">Agregar Imagen</button>
        </form>

        <div class="row justify-content-center g-1">
        <?php foreach ($sliders as $s): ?>
            <div class="col-md-4 col-lg-3 d-flex justify-content-center">
                <div class="card shadow-sm mb-3" style="width: 100%; max-width: 300px;">
                    <img src="imagenes/<?= htmlspecialchars($s['IMAGEN']) ?>" class="img-fluid" style="height:200px; object-fit:cover; border-bottom:3px solid #007bff;">
                    <div class="card-body text-center">
                        <form method="POST" class="delete-form">
                            <input type="hidden" name="accion" value="eliminar_slider">
                            <input type="hidden" name="slider_id" value="<?= $s['SLIDER_REL'] ?>">
                            <button type="button" class="btn btn-danger btn-sm btn-delete">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function(){
        const form = this.closest('form');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción eliminará la imagen permanentemente!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
</body>
</html>