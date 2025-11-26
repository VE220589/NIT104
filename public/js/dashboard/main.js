

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    let today = new Date();
    let hour = today.getHours();
    let greeting = '';

    if (hour < 12) {
        greeting = 'Buenos días';
    } else if (hour < 19) {
        greeting = 'Buenas tardes';
    } else {
        greeting = 'Buenas noches';
    }

    document.getElementById('greeting').textContent = greeting;
});

function logOut() {
    Swal.fire({
        title: 'Advertencia',
        text: '¿Quiere cerrar la sesión?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then(result => {
        if (result.isConfirmed) {

            fetch(API_AUTH + 'logOut', { method: 'GET' })
                .then(r => r.json())
                .then(response => {

                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sesión cerrada',
                            text: response.message
                        }).then(() => {
                            window.location.href = BASE_URL;
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.exception
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire("Error", "No se pudo cerrar la sesión", "error");
                });

        } else {
            Swal.fire({
                icon: 'info',
                title: 'Sesión activa',
                text: 'Puede continuar con la sesión'
            });
        }
    });
}
