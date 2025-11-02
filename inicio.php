<?php
require_once("MasterCL.php");
require_once("funciones/fxGeneral.php");
$conexion = fxAbrirConexion();

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != "usuario") {
    header("Location: index.php");
    exit;
}

// Contenido principal
$msDatos = $conexion->query("SELECT * FROM RESER010 LIMIT 1");
$inicio = $msDatos->fetch(PDO::FETCH_ASSOC);

// Slider
$msDatos2 = $conexion->query("SELECT * FROM RESER011 ORDER BY ORDEN ASC");
$sliders = $msDatos2->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- SLIDER PRINCIPAL -->
<div class="container py-4 carousel-container">
    <?php if (count($sliders) > 0): ?>
    <div id="sliderViaGo" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="3500">
        <div class="carousel-inner">
            <?php foreach($sliders as $i => $s): ?>
            <div class="carousel-item <?= $i==0 ? 'active' : '' ?>">
                <img src="imagenes/<?= htmlspecialchars($s['IMAGEN']) ?>" class="d-block w-100" alt="Imagen del viaje">
            </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#sliderViaGo" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#sliderViaGo" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    <?php endif; ?>
</div>

<!-- SECCIÓN MISIÓN -->
<div class="section">
    <div class="row align-items-center">
        <div class="col-md-6 text">
            <h2>Misión</h2>
            <p><?= htmlspecialchars($inicio['MISION'] ?? 'Nuestra misión es ofrecer experiencias únicas de viaje que conecten a las personas con el mundo y con ellas mismas.') ?></p>
        </div>
        <div class="col-md-6 p-0">
            <img src="img/viajera.png" alt="Misión">
        </div>
    </div>
</div>

<!-- SECCIÓN VISIÓN -->
<div class="section">
    <div class="row align-items-center flex-md-row-reverse">
        <div class="col-md-6 text">
            <h2>Visión</h2>
            <p><?= htmlspecialchars($inicio['VISION'] ?? 'Ser la plataforma líder en experiencias turísticas sostenibles, promoviendo la cultura y el bienestar global.') ?></p>
        </div>
        <div class="col-md-6 p-0">
            <img src="img/vision.png" alt="Visión">
        </div>
    </div>
</div>

<!-- BOTÓN FINAL -->
<div class="text-center mb-5">
    <a href="usuario.php" class="btn btn-primary btn-lg">Explorar Próximos Viajes</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>