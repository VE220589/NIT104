// ===============================
// CONFIG Y EVENTOS PRINCIPALES
// ===============================

const API_USUARIOS = '/NIT104/public/api/usuarios/';

document.addEventListener('DOMContentLoaded', () => {
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.Modal.init(document.querySelectorAll('.modal'));
    cargarUsuarios();
    cargarCombos();
});

// ===============================
// Cargar combos (TIPO y ESTADO)
// ===============================

// ===============================
// Cargar combos (TIPO y ESTADO)
// ===============================

function cargarCombos() {
    // Para tipos de usuario
    cargarCombo('getTipo', 'id_tipo_usuario', 'id', 'nombre');

    // Para estados de usuario
    //cargarCombo('getEstado', 'id_estado_usuario', 'id', 'nombre');
}

function cargarCombo(endpoint, selectId, idField, textField) {
    fetch(API_USUARIOS + endpoint)
        .then(res => res.json())
        .then(json => {
            if (!json.status) return;

            const select = document.getElementById(selectId);
            select.innerHTML = ''; // Limpiar opciones anteriores

            // Agregar opciones desde la base de datos
            json.dataset.forEach(item => {
                select.innerHTML += `<option value="${item[idField]}">${item[textField]}</option>`;
            });

            // Inicializa el select de Materialize
            M.FormSelect.init(select);
        })
        .catch(err => console.error("Error cargando combo:", err));
}



// ===============================
// CARGA DE TABLA
// ===============================

function cargarUsuarios() {
    fetch(API_USUARIOS + 'index')
        .then(res => res.json())
        .then(json => {
            if (json.status) llenarTabla(json.dataset);
            else Swal.fire("Error", json.exception, "error");
        })
        .catch(() => Swal.fire("Error", "No se pudo cargar la tabla", "error"));
}

function llenarTabla(dataset) {
    let content = '';

    dataset.forEach(row => {
        content += `
            <tr>
                <td>${row.last_name}</td>
                <td>${row.name}</td>
                <td>${row.email}</td>
                <td>${row.username}</td>
                <td>${row.tipo}</td>
                <td>${row.is_active}</td>
                <td>
                    <a class="btn blue tooltipped" data-tooltip="Actualizar"
                        onclick="openUpdateDialog(${row.id})">
                        <i class="material-icons">mode_edit</i>
                    </a>
                    <a class="btn red tooltipped" data-tooltip="Eliminar"
                        onclick="openDeleteDialog(${row.id})">
                        <i class="material-icons">delete</i>
                    </a>
                </td>
            </tr>
        `;
    });

    document.getElementById('tbody-rows').innerHTML = content;
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

// ===============================
// CREAR
// ===============================

window.openCreateDialog = function () {
    const modal = M.Modal.getInstance(document.getElementById('save-modal'));

    document.getElementById('save-form').reset();
    document.getElementById('modal-title').textContent = 'Crear usuario';

    document.getElementById('alias_usuario').disabled = false;
    document.getElementById('alias_usuario').readOnly = false;
    document.getElementById('clave_usuario').disabled = false;
    document.getElementById('confirmar_clave').disabled = false;

    document.getElementById('id_usuario').value = '';


    cargarCombos();
    modal.open();
};

// ===============================
// ACTUALIZAR
// ===============================

window.openUpdateDialog = function (id) {
    const modal = M.Modal.getInstance(document.getElementById('save-modal'));

    const form = new FormData();
    form.append('id_usuario', id);

    fetch(API_USUARIOS + 'readOne', {
        method: 'POST',
        body: form
    })
    .then(res => res.json())
    .then(json => {
        if (!json.status) return Swal.fire("Error", json.exception, "error");

        const d = json.dataset;

        document.getElementById('modal-title').textContent = 'Actualizar usuario';
        document.getElementById('id_usuario').value = d.id;
        document.getElementById('nombres_usuario').value = d.name;
        document.getElementById('apellidos_usuario').value = d.last_name;
        document.getElementById('correo_usuario').value = d.email;
        document.getElementById('alias_usuario').value = d.username;

        // Desactivar alias y claves
        document.getElementById('alias_usuario').readOnly = true;
        document.getElementById('clave_usuario').disabled = true;
        document.getElementById('confirmar_clave').disabled = true;

        // Asignar valores de selects y reinicializar Materialize
        setTimeout(() => {
            document.getElementById('id_tipo_usuario').value = d.role_id;

            // Reinicializar selects de Materialize para que tomen el valor
            const selects = document.querySelectorAll('select');
            M.FormSelect.init(selects);
        }, 50);

        M.updateTextFields();
        modal.open();
    })
    .catch(() => Swal.fire("Error", "No se pudo leer el usuario", "error"));
};


// ===============================
// GUARDAR (CREATE / UPDATE)
// ===============================

document.getElementById('save-form').addEventListener('submit', e => {
    e.preventDefault();

    const form = new FormData(e.target);
    const isUpdate = form.get('id_usuario') !== '';
    const action = isUpdate ? 'update' : 'create';

    fetch(API_USUARIOS + action, {
        method: 'POST',
        body: form
    })
    .then(res => res.json())
    .then(json => {
        console.log(json);//Para ver la respuesta del json en la consola
        if (!json.status) {
            let errorMessage = 
                json.exception ||
                json.error_db ||
                JSON.stringify(json.errors) ||
                json.message ||
                "Ocurrió un error desconocido";

            Swal.fire("Error", errorMessage, "error");
            return;
        }

        Swal.fire("Éxito", json.message, "success");
        M.Modal.getInstance(document.getElementById('save-modal')).close();
        cargarUsuarios();
    })
    .catch(() => Swal.fire("Error", "No se pudo guardar el usuario", "error"));
});


// ===============================
// ELIMINAR
// ===============================

window.openDeleteDialog = function (id) {

    Swal.fire({
        title: "¿Eliminar usuario?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar"
    })
        .then(result => {
            if (!result.isConfirmed) return;

            const form = new FormData();
            form.append('id_usuario', id);

            fetch(API_USUARIOS + 'delete', {
                method: 'POST',
                body: form
            })
                .then(res => res.json())
                .then(json => {
                    if (!json.status) return Swal.fire("Error", json.exception, "error");

                    Swal.fire("Eliminado", "Usuario eliminado correctamente", "success");
                    cargarUsuarios();
                })
                .catch(() => Swal.fire("Error", "No se pudo eliminar", "error"));
        });
};

// ===============================
// BUSCAR
// ===============================

document.getElementById('search-form').addEventListener('submit', e => {
    e.preventDefault();

    // 1. Capturamos el valor del input directamente
    const searchInput = document.getElementById('search');
    const searchValue = searchInput.value.trim();

    // 2. Validación simple
    if (!searchValue) {
        Swal.fire("Sin resultados", "El término de búsqueda está vacío", "info");
        return;
    }

    // 3. Creamos FormData manualmente
    const form = new FormData();
    form.append('search', searchValue);

    // 4. Llamamos a la API
    fetch(API_USUARIOS + 'search', {
        method: 'POST',
        body: form
    })
        .then(res => res.json())
        .then(json => {
            if (!json.status || !json.dataset || json.dataset.length === 0) {
                Swal.fire("Sin resultados", "No hay datos coincidentes", "info");
                llenarTabla([]);
                return;
            }

            // 5. Llenamos la tabla correctamente
            llenarTabla(json.dataset);
        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo realizar la búsqueda", "error");
        });
});
