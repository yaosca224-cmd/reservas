<?php
require_once("masterApp.php");
require_once("funciones/fxAdmin.php");

$editar_evento = isset($_GET['editar']) ? devuelveEventos() : null;
$eventos = !$editar_evento ? devuelveEventos() : obtenerEventos();
?>
<div class="container py-4">
    <h2 class="mb-4 text-center text-primary">📋 Panel de Administración de Eventos</h2>
    <!-- Mensaje de acción -->
        <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3">
            <?= htmlspecialchars($_GET['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Formulario Crear / Editar (arriba) -->
        <div class="card mb-4 shadow-sm border-primary" id="formEvento">
            <div class="card-header bg-primary text-white">
                <?= $editar_evento ? "✏️ Editar Evento" : "➕ Crear Nuevo Evento" ?>
            </div>
    
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <?php if($editar_evento): ?>
                        <input type="hidden" name="id" value="<?= $editar_evento['EVENTOS_REL'] ?>">
                    <?php endif; ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Título</label>
                            <input type="text" name="titulo" class="form-control" required value="<?= $editar_evento['TITULO_002'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Fecha</label>
                            <input type="date" name="fecha" class="form-control" required value="<?= $editar_evento['FECHA_002'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Cupos</label>
                            <input type="number" name="cupos" class="form-control" required value="<?= $editar_evento['CUPOS_002'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Imagen <?= $editar_evento ? "(opcional)" : "" ?></label>
                            <input type="file" name="imagen" class="form-control">
                            <?php if($editar_evento && $editar_evento['IMAGEN_002']): ?>
                                <img src="imagenes/<?= $editar_evento['IMAGEN_002'] ?>" class="img-fluid mt-2" style="height:100px; object-fit:cover; border-radius:8px;">
                            <?php endif; ?>
                        </div>
                        <div class="col-12">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control" required><?= $editar_evento['DESC_002'] ?? '' ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Precio por persona ($)</label>
                            <input type="number" step="0.01" name="precio" class="form-control" required value="<?= $editar_evento['PRECIO_002'] ?? '' ?>">
                        </div>
                    </div>
                    <button class="btn btn-success mt-3"><?= $editar_evento ? "Guardar Cambios" : "Crear Evento" ?></button>
                    <?php if($editar_evento): ?>
                        <a href="admin.php" class="btn btn-secondary mt-3">Cancelar</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Grid de eventos (abajo) -->
        <div class="row g-4">
            <?php foreach($eventos as $evento): ?>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card card-evento shadow h-100 border-0">
                    <?php if($evento["IMAGEN_002"]): ?>
                        <img src="imagenes/<?= $evento["IMAGEN_002"] ?>" class="card-img-top img-evento">
                    <?php else: ?>
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center img-evento">Sin Imagen</div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($evento["TITULO_002"]) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($evento["DESC_002"]) ?></p>
                        <div class="mb-2">
                            <span class="badge bg-info text-dark">Fecha: <?= $evento["FECHA_002"] ?></span>
                            <span class="badge bg-success">Cupos: <?= $evento["CUPOS_002"] ?></span>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-warning text-dark">Precio: $<?= number_format($evento["PRECIO_002"],2) ?></span>
                        </div>
                        <div class="mt-auto d-grid gap-1">
                            <a href="?editar=<?= $evento["EVENTOS_REL"] ?>#formEvento" class="btn btn-primary btn-sm">✏️ Editar</a>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalBorrar<?= $evento['EVENTOS_REL'] ?>">🗑️ Borrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal borrar -->
            <div class="modal fade" id="modalBorrar<?= $evento['EVENTOS_REL'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-danger">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Confirmar Borrado</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Deseas eliminar el evento <strong><?= htmlspecialchars($evento["TITULO_002"]) ?></strong>? Esta acción no se puede deshacer.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <a href="?borrar=<?= $evento['EVENTOS_REL'] ?>" class="btn btn-danger">Sí, borrar</a>
                        </div>
                    </div>
                </div>
            </div><?php endforeach; ?>

            <?php if(count($eventos)==0): ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">No hay eventos registrados</div>
            </div><?php endif; ?>
        </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>