<!-- app/Views/dashboard/index.php -->

<?= $this->extend('layouts/dashboard_main') ?>

<?= $this->section('title') ?>Iniciar sesión<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <form method="post" id="session-form">
            <div class="input-field col s12 m6 offset-m3">
                <i class="material-icons prefix">person_pin</i>
                <input id="alias" type="text" name="alias_usuario" required />
                <label for="alias">Alias</label>
            </div>
            <div class="input-field col s12 m6 offset-m3">
                <i class="material-icons prefix">security</i>
                <input id="clave" type="password" name="clave_usuario" required />
                <label for="clave">Clave</label>
            </div>
            <div class="col s12 center-align">
                <button type="submit" class="btn waves-effect blue">
                    <i class="material-icons">send</i>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/NIT104/public/js/dashboard/index.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection() ?>


