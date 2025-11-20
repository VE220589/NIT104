<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Administrar clientes');
?>

<div class="row">
    <!-- Formulario de búsqueda -->
    <form method="post" id="search-form">
        <div class="input-field col s6 m4">
            <i class="material-icons prefix">search</i>
            <input id="search" type="text" name="search" required/>
            <label for="search">Buscador</label>
        </div>
        <div class="input-field col s6 m4">
            <button type="submit" class="btn waves-effect green tooltipped" data-tooltip="Buscar"><i class="material-icons">check_circle</i></button>
        </div>
    </form>
    <div class="input-field center-align col s12 m4">
        <!-- Enlace para abrir la caja de dialogo (modal) al momento de crear un nuevo registro -->
        <a href="#" onclick="openCreateDialog()" class="btn waves-effect indigo tooltipped" data-tooltip="Crear"><i class="material-icons">add_circle</i></a>
    </div>
</div>

<!-- Tabla para mostrar los registros existentes -->
<table class="highlight">
    <!-- Cabeza de la tabla para mostrar los títulos de las columnas -->
    <thead>
        <tr>
            <th>APELLIDOS</th>
            <th>NOMBRES</th>
            <th>CORREO</th>
            <th>DUI</th>
            <th class="actions-column">ACCIONES</th>
        </tr>
    </thead>
    <!-- Cuerpo de la tabla para mostrar un registro por fila -->
    <tbody id="tbody-rows">
    </tbody>
</table>

<!-- Componente Modal para mostrar una caja de dialogo -->
<div id="save-modal" class="modal">
    <div class="modal-content">
        <!-- Título para la caja de dialogo -->
        <h4 id="modal-title" class="center-align"></h4>
        <!-- Formulario para crear o actualizar un registro -->
        <form method="post" id="save-form">
            <!-- Campo oculto para asignar el id del registro al momento de modificar -->
            <input class="hide" type="number" id="id_cliente" name="id_cliente"/>
            <div class="row">
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">person</i>
                    <input id="nombres_cliente" type="text" name="nombres_cliente" class="validate" required/>
                    <label for="nombres_cliente">Nombres</label>
                </div>
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">person</i>
                    <input id="apellidos_cliente" type="text" name="apellidos_cliente" class="validate" required/>
                    <label for="apellidos_cliente">Apellidos</label>
                </div>
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">email</i>
                    <input id="correo_cliente" type="email" name="correo_cliente" class="validate" required/>
                    <label for="correo_cliente">Correo</label>
                </div>
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">person_pin</i>
                    <input id="dui_cliente" type="text" name="dui_cliente" class="validate" required/>
                    <label for="dui_cliente">DUI</label>
                </div>
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">person_pin</i>
                    <input id="telefono_cliente" type="text" name="telefono_cliente" class="validate" required/>
                    <label for="telefono_cliente">Teléfono</label>
                </div>
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">person_pin</i>
                    <input id="nacimiento_cliente" type="date" name="nacimiento_cliente" class="validate" required/>
                    <label for="nacimiento_cliente">Fecha de nacimiento</label>
                </div>
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">person_pin</i>
                    <input id="direccion_cliente" type="text" name="direccion_cliente" class="validate" required/>
                    <label for="direccion_cliente">Dirección</label>
                </div>
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">security</i>
                    <input id="clave_cliente" type="password" name="clave_cliente" class="validate" required/>
                    <label for="clave_cliente">Clave</label>
                </div>
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">security</i>
                    <input id="confirmar_clave" type="password" name="confirmar_clave" class="validate" required/>
                    <label for="confirmar_clave">Confirmar clave</label>
                </div>
                <div class="col s12 m6">
                    <p>
                        <div class="switch">
                            <span>Estado:</span>
                            <label>
                                <i class="material-icons">visibility_off</i>
                                <input id="estado_cliente" type="checkbox" name="estado_cliente" checked/>
                                <span class="lever"></span>
                                <i class="material-icons">visibility</i>
                            </label>
                        </div>
                    </p>
                </div>
            </div>
            <div class="row center-align">
                <a href="#" class="btn waves-effect grey tooltipped modal-close" data-tooltip="Cancelar"><i class="material-icons">cancel</i></a>
                <button type="submit" class="btn waves-effect blue tooltipped" data-tooltip="Guardar"><i class="material-icons">save</i></button>
            </div>
        </form>
    </div>
</div>

<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Dashboard_Page::footerTemplate('clientes.js');
?>