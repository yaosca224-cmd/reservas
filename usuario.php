<?php
require_once("MasterCL.php");
require_once("funciones/fxGeneral.php");
$conexion = fxAbrirConexion();

// Solo usuarios logueados
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != "usuario") {
    header("Location: index.php");
    exit;
}

// Traer eventos
$stmt = $conexion->query("SELECT * FROM RESER002 ORDER BY FECHA_002 ASC");
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
    <h2 class="mb-4 text-center text-primary">🎉 Eventos Disponibles</h2>
    <div class="row g-4">
        <?php foreach ($eventos as $evento): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden">
                <?php if($evento["IMAGEN_002"]): ?>
                <img src="imagenes/<?= $evento["IMAGEN_002"] ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                <?php else: ?>
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:200px;">Sin Imagen</div>
                <?php endif; ?>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($evento["TITULO_002"]) ?></h5>
                    <div class="mb-2">
                        <span class="badge bg-info text-dark">Fecha: <?= $evento["FECHA_002"] ?></span>
                        <span class="badge <?= $evento["CUPOS_002"] > 0 ? 'bg-success' : 'bg-danger' ?>" id="cupos-badge-<?= $evento['EVENTOS_REL'] ?>">
                            <?= $evento["CUPOS_002"] > 0 ? "Cupos: ".$evento["CUPOS_002"] : "Agotado" ?>
                        </span>
                    </div>
                    <button class="btn btn-primary w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#modalEvento<?= $evento['EVENTOS_REL'] ?>">Ver</button>
                </div>
            </div>
        </div>

        <!-- Modal evento -->
        <div class="modal fade" id="modalEvento<?= $evento['EVENTOS_REL'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><?= htmlspecialchars($evento["TITULO_002"]) ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <?php if($evento["IMAGEN_002"]): ?>
                        <img src="imagenes/<?= $evento["IMAGEN_002"] ?>" class="img-fluid mb-3 rounded">
                        <?php endif; ?>
                        <p><?= htmlspecialchars($evento["DESC_002"]) ?></p>
                        <p><strong>Fecha:</strong> <?= $evento["FECHA_002"] ?>
                        <strong>Cupos:</strong> <span id="cupos<?= $evento['EVENTOS_REL'] ?>"><?= $evento["CUPOS_002"] ?></span></p>

                        <?php if($evento["CUPOS_002"] > 0): ?>
                        <form class="mt-3 reservar-form" data-evento="<?= $evento['EVENTOS_REL'] ?>" data-precio="<?= $evento['PRECIO_002'] ?>">
                            <div class="mb-2">
                                <input type="text" class="form-control" name="nombre" placeholder="Nombre completo" required>
                            </div>
                            <div class="mb-2">
                                <input type="email" class="form-control" name="email" placeholder="Correo electrónico" required>
                            </div>
                            <div class="mb-2">
                                <label>Cantidad de cupos</label>
                                <input type="number" class="form-control" name="cantidad" value="1" min="1" max="<?= $evento['CUPOS_002'] ?>" required>
                            </div>
                            <div class="mb-2">
                                <strong>Valor del viaje $<?= $evento['PRECIO_002'] ?></span></strong>
                            </div>
                            <div class="mb-2">
                                <strong>Total a pagar: $<span class="total"><?= $evento['PRECIO_002'] ?></span></strong>
                            </div>
                            <div class="mb-2">
                                <select class="form-select" name="pago" required>
                                    <option value="">Seleccione forma de pago</option>
                                    <option value="tarjeta">Tarjeta de crédito/débito</option>
                                    <option value="efectivo">Efectivo</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Reservar</button>
                        </form>
                        <?php else: ?>
                        <button class="btn btn-secondary w-100 mt-3" disabled>Agotado</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.reservar-form').forEach(form => {
    const eventoId = form.dataset.evento;
    const inputCantidad = form.querySelector('input[name="cantidad"]');
    const totalSpan = form.querySelector('.total');
    const precioCupo = parseFloat(form.dataset.precio);

    totalSpan.innerText = (inputCantidad.value * precioCupo).toFixed(2);

    inputCantidad.addEventListener('input', () => {
        totalSpan.innerText = ((Number(inputCantidad.value) || 0) * precioCupo).toFixed(2);


    });

    form.addEventListener('submit', e => {
        e.preventDefault();
        let formData = new FormData(form);
        formData.append('evento_id', eventoId);
        fetch('reservar.php',{ method:'POST', body:formData })
        .then(res=>res.json())
        .then(data=>{
            const cuposSpan = document.getElementById('cupos'+eventoId);
            const badge = document.getElementById('cupos-badge-'+eventoId);
            if(data.success){
                cuposSpan.innerText = data.cupos;
                badge.innerText = data.cupos>0?"Cupos: "+data.cupos:"Agotado";
                badge.className = data.cupos>0?"badge bg-success":"badge bg-danger";
                if(data.cupos<=0) form.querySelectorAll('input,select,button').forEach(el=>el.disabled=true);
                form.reset(); totalSpan.innerText=(1*precioCupo).toFixed(2);
                Swal.fire({title:'¡Reserva exitosa!', text:data.message, icon:'success', timer:5000, showConfirmButton:false, position:'center', didClose:()=>bootstrap.Modal.getInstance(document.getElementById('modalEvento'+eventoId)).hide()});
            } else { Swal.fire({title:'Error', text:data.message, icon:'error', timer:3000, showConfirmButton:false, position:'center'}); }
        });
    });
});
</script>
</body>
</html>