
<?= $this->extend('layouts/dashboard_public') ?>

<?= $this->section('title') ?>Iniciar sesión<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container">
   <!-- Se muestra un saludo de acuerdo con la hora del cliente -->
<div class="row">
    <h4 class="center-align blue-text" id="greeting"></h4>
</div>

<!-- Se muestran las gráficas de acuerdo con algunos datos disponibles en la base de datos -->
<div class="row">
    <div class="col s12 m6">
        <!-- Se muestra una gráfica de barra con la cantidad de productos por categoría -->
        <canvas id="chart1"></canvas>
    </div>
    <div class="col s12 m6">
        <!-- Se muestra una gráfica de pastel con el porcentaje de productos por categoría -->
        <canvas id="chart2"></canvas>
    </div>
</div>

<!-- Importación del archivo para generar gráficas en tiempo real. Para más información https://www.chartjs.org/ -->
<script type="text/javascript" src="../../resources/js/chart.js"></script>
</div>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/NIT104/public/js/dashboard/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection() ?>

