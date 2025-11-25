// ===============================
// CONFIG Y EVENTOS PRINCIPALES
// ===============================

const API_TICKETS = '/NIT104/public/api/tickets/';

document.addEventListener('DOMContentLoaded', () => {
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    M.Modal.init(document.querySelectorAll('.modal'));
    cargarTickets();
    const select = document.getElementById('prioridad');
    M.FormSelect.init(select);
    const select1 = document.getElementById('estado');
    M.FormSelect.init(select1);
    const select2 = document.getElementById('id_tipo_ticket');
    M.FormSelect.init(select2);
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
    cargarCombo('getServices', 'id_servicio', 'id', 'desc');

    // Para cargar los usuarios
    cargarCombo('getUsuarios', 'id_asignado', 'id', 'nombre');
}

function cargarCombo(endpoint, selectId, idField, textField) {
    fetch(API_TICKETS + endpoint)
        .then(res => res.json())
        .then(json => {
             console.log(json);
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

function cargarTickets() {
    fetch(API_TICKETS + 'index')
        .then(res => res.json())
        .then(json => {
            console.log(json); // Ver respuesta en consola

            if (json.status) {
                // Verificar si dataset tiene datos
                if (json.dataset && json.dataset.length > 0) {
                    llenarTabla(json.dataset);
                } else {
                    Swal.fire("Sin datos", "No se encontraron usuarios.", "info");
                }
            } else {
                Swal.fire("Error", json.exception, "error");
            }
        })
        .catch(() => Swal.fire("Error", "No se pudo cargar la tabla", "error"));
}


function llenarTabla(dataset) {
    let content = '';
    dataset.forEach(row => {
        if(row.assigned_to != null && row.status != "closed"){
            content += `
                <tr>
                    <td>${row.ticket_number}</td>
                    <td>${row.title}</td>
                    <td>${row.description}</td>
                    <td>${row.ticket_type}</td>
                    <td>${row.status}</td>
                    <td>${row.priority}</td>
                    <td>${row.service_name}</td>
                    <td>${row.creado_por}</td>
                    <td>${row.asignado_a}</td>
                    <td>${row.cerrado_por}</td>
                <td>
                        <a class="btn blue tooltipped" style="margin-top: 5px;" data-tooltip="Actualizar"
                            onclick="openUpdateDialog(${row.id})">
                            <i class="material-icons">mode_edit</i>
                        </a>

                        <a class="btn red tooltipped" style="margin-top: 5px;" data-tooltip="Cerrar ticket"
                            onclick="openDeleteDialog(${row.id})">
                            <i class="material-icons">exit_to_app</i>
                        </a>
                    </td>

                </tr>
            `; 
        }else if(row.assigned_to == null && row.status != "closed") {
                content += `
            <tr>
                <td>${row.ticket_number}</td>
                <td>${row.title}</td>
                <td>${row.description}</td>
                <td>${row.ticket_type}</td>
                <td>${row.status}</td>
                <td>${row.priority}</td>
                <td>${row.service_name}</td>
                <td>${row.creado_por}</td>
                <td>${row.asignado_a}</td>
                <td>${row.cerrado_por}</td>
               <td>
                    <a class="btn blue tooltipped" style="margin-top: 5px;" data-tooltip="Actualizar"
                        onclick="openUpdateDialog(${row.id})">
                        <i class="material-icons">mode_edit</i>
                    </a>
                </td>
            </tr>
        `;
        }else{
                content += `
            <tr>
                <td>${row.ticket_number}</td>
                <td>${row.title}</td>
                <td>${row.description}</td>
                <td>${row.ticket_type}</td>
                <td>${row.status}</td>
                <td>${row.priority}</td>
                <td>${row.service_name}</td>
                <td>${row.creado_por}</td>
                <td>${row.asignado_a}</td>
                <td>${row.cerrado_por}</td>
                <td>Ticket cerrado</td>
            </tr>
        `;
        }
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
    document.getElementById('modal-title').textContent = 'Crear ticket';
    document.getElementById('id_ticket').value = '';
    document.getElementById('estado_container').style.display = "none";
    document.getElementById('check_container').style.display = "block";
    document.getElementById('usuarios_container').style.display = "none";

    cargarCombos();
    modal.open();
};

// ===============================
// ACTUALIZAR
// ===============================

window.openUpdateDialog = function (id) {
    const modal = M.Modal.getInstance(document.getElementById('save-modal'));
    document.getElementById('miCheck').checked = false;
    const form = new FormData();
    form.append('id_ticket', id);

    fetch(API_TICKETS + 'readOne', {
        method: 'POST',
        body: form
    })
    .then(res => res.json())
    .then(json => {
        if (!json.status) return Swal.fire("Error", json.exception, "error");

        const d = json.dataset;

        document.getElementById('modal-title').textContent = 'Actualizar ticket';
        document.getElementById('id_ticket').value = d.id;
        document.getElementById('title').value = d.title;
        document.getElementById('desc').value = d.description;
        //Activar los campos de estado y usuario
        document.getElementById('estado_container').style.display = "block";
        document.getElementById('usuarios_container').style.display = "none";
        // Asignar valores de selects y reinicializar Materialize
        setTimeout(() => {
            document.getElementById('id_tipo_ticket').value = d.ticket_type;
            document.getElementById('prioridad').value = d.priority;
            document.getElementById('id_servicio').value = d.service_id;
            document.getElementById('estado').value = d.status;
            if (d.assigned_to === null) {
                document.getElementById('miCheck').checked = false
                document.getElementById('check_container').style.display = "block";
                document.getElementById('id_asignado').value = 2;
            } else {
                document.getElementById('usuarios_container').style.display = "block";
                 document.getElementById('check_container').style.display = "none";
                document.getElementById('id_asignado').value = d.assigned_to;
            }

            // Reinicializar selects de Materialize para que tomen el valor
            const selects = document.querySelectorAll('select');
            M.FormSelect.init(selects);
        }, 50);

        M.updateTextFields();
        modal.open();
    })
    .catch(() => Swal.fire("Error", "No se pudo leer el TICKET", "error"));
};


document.getElementById('miCheck').addEventListener('change', function () {
    if (this.checked) {
        document.getElementById('usuarios_container').style.display = "block";
    } else {
         document.getElementById('usuarios_container').style.display = "none";
    }
});

// ===============================
// GUARDAR (CREATE / UPDATE)
// ===============================

document.getElementById('save-form').addEventListener('submit', e => {
    e.preventDefault();

    const form = new FormData(e.target);

    // --- CONTROL DE ASIGNADO ---
    const checkUsers = document.getElementById('usuarios_container');
    const asignadoSelect = document.getElementById('id_asignado');

    if (checkUsers.style.display === "block") {
         // Si está visible → enviar valor del select (aunque esté vacío)
        form.set('id_asignado', asignadoSelect.value);
    } else if (checkUsers.style.display === "none") {

        // Si está oculto → asignado debe ser NULL
        form.set('id_asignado', null);
    }

    // Saber si es update o create
    const isUpdate = form.get('id_ticket') !== '';
    const action = isUpdate ? 'update' : 'create';

    fetch(API_TICKETS + action, {
        method: 'POST',
        body: form
    })
    .then(res => res.json())
    .then(json => {
        console.log(json);

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
        cargarTickets();
    })
    .catch(() => Swal.fire("Error", "No se pudo guardar el TICKET", "error"));
});



// ===============================
// ELIMINAR
// ===============================

window.openDeleteDialog = function (id) {

    Swal.fire({
        title: "¿Desea cerrar el ticket?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar"
    })
        .then(result => {
            if (!result.isConfirmed) return;

            const form = new FormData();
            form.append('id_ticket', id);

            fetch(API_TICKETS + 'deletelogic', {
                method: 'POST',
                body: form
            })
                .then(res => res.json())
                .then(json => {
                    console.log(json);
                    if (!json.status) return Swal.fire("Error", json.exception, "error");

                    Swal.fire("Eliminado", "Ticket cerrado correctamente", "success");
                    cargarTickets();
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
