<?php
require_once("MasterCL.php");
require_once("funciones/fxGeneral.php");
require_once("funciones/fxReservas.php");

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != "usuario") {
    header("Location: index.php");
    exit;
}
$reservas = fxObtenerReservasPorUsuario($_SESSION["usuario"]);
?>

<div class="container py-5">
    <h2 class="mb-4 text-center text-primary">📋 Mis Reservas</h2>
    <?php if (count($reservas) == 0): ?>
        <div class="alert alert-info text-center">No has realizado reservas todavía.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Evento</th>
                        <th>Fecha del evento</th>
                        <th>Cantidad</th>
                        <th>Total pagado</th>
                        <th>Fecha de reserva</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($reservas as $i => $r): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($r["TITULO_002"]) ?></td>
                            <td><?= $r["FECHA_002"] ?></td>
                            <td><?= $r["CANTIDAD"] ?></td>
                            <td>$<?= number_format($r["TOTAL_PAGO"], 2) ?></td>
                            <td><?= $r["FECHA_RESERVA"] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>