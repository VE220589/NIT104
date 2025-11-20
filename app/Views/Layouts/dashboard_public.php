<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Dashboard - <?= $this->renderSection('title') ?></title>

    <link rel="icon" type="image/png" href="/NIT104/public/resources/img/logo.png">
    <link rel="stylesheet" href="/NIT104/public/resources/css/materialize.min.css">
    <link rel="stylesheet" href="/NIT104/public/resources/css/material_icons.css">
    <link rel="stylesheet" href="/NIT104/public/resources/css/dashboard.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>

<header>
                        <div class="navbar-fixed">
                            <nav class="teal">
                                <div class="nav-wrapper">
                                    <a href="main" class="brand-logo"><img src="../../resources/img/logo.png" height="60"></a>
                                    <a href="#" data-target="mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                                    <ul class="right hide-on-med-and-down">
                                        <li><a href="productos.php"><i class="material-icons left">shop</i>Productos</a></li>
                                        <li><a href="categorias.php"><i class="material-icons left">shop_two</i>Categorías</a></li>
                                        <li><a href="clientes.php"><i class="material-icons left">contacts</i>Clientes</a></li>
                                        <li><a href="usuarios1"><i class="material-icons left">group</i>Usuarios</a></li>
                                        <li><a href="#" class="dropdown-trigger" data-target="dropdown"><i class="material-icons left">verified_user</i>Cuenta: <b><?= session()->get('alias_usuario') ?? 'Invitado' ?></b></a></li>
                                    </ul>
                                    <ul id="dropdown" class="dropdown-content">
                                        <li><a href="#" onclick="openProfileDialog()"><i class="material-icons">face</i>Editar perfil</a></li>
                                        <li><a href="#" onclick="openPasswordDialog()"><i class="material-icons">lock</i>Cambiar clave</a></li>
                                        <li><a href="#" onclick="logOut()"><i class="material-icons">clear</i>Salir</a></li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <ul class="sidenav" id="mobile">
                            <li><a href="productos.php"><i class="material-icons">shop</i>Productos</a></li>
                            <li><a href="categorias.php"><i class="material-icons">shop_two</i>Categorías</a></li>
                            <li><a href="clientes.php"><i class="material-icons">contacts</i>Clientes</a></li>
                            <li><a href="usuarios1"><i class="material-icons">group</i>Usuarios</a></li>
                            <li><a class="dropdown-trigger" href="#" data-target="dropdown-mobile"><i class="material-icons">verified_user</i>Cuenta: <b><?= session()->get('alias_usuario') ?? 'Invitado' ?></b></a></li>
                        </ul>
                        <ul id="dropdown-mobile" class="dropdown-content">
                            <li><a href="#" onclick="openProfileDialog()">Editar perfil</a></li>
                            <li><a href="#" onclick="openPasswordDialog()">Cambiar clave</a></li>
                            <li><a href="#" onclick="logOut()">Salir</a></li>
                        </ul>
                    </header>

<main class="container">
    <h3 class="center-align"><?= $this->renderSection('title') ?></h3>

    <?= $this->renderSection('content') ?>
</main>

<footer class="page-footer teal">
    <div class="container">
        <p class="white-text">Derechos reservados 2025</p>
    </div>
</footer>

<script src="/NIT104/public/resources/js/materialize.min.js"></script>
<script src="/NIT104/public/resources/js/sweetalert.min.js"></script>
<script src="/NIT104/public/resources/components.js"></script>
<script src="/NIT104/public/js/dashboard/initialization.js"></script>

<?= $this->renderSection('scripts') ?>

</body>
</html>

