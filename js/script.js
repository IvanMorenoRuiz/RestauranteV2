function submitForm() {
    document.forms["formulario-filtros"].submit();
}

function limpiarForm() {
    var formulario = document.forms["formulario-filtros"];
    formulario.reset();
    updateContent();
}

function toggleFilters() {
    var filtroSalas = document.querySelectorAll('.filtro-salas');
    for (var i = 0; i < filtroSalas.length; i++) {
        filtroSalas[i].style.display = (filtroSalas[i].style.display === 'flex') ? 'none' : 'flex';
    }
}

function confirmarCerrarSesion() {
    // Muestra un cuadro de diálogo de confirmación
    Swal.fire({
    title: '¿Estás seguro?',
    text: '¿Quieres cerrar la sesión?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, cerrar sesión',
    cancelButtonText: 'Cancelar'
    }).then((result) => {
    // Si el usuario confirma, redirecciona a la siguiente página
    if (result.isConfirmed) {
        window.location.href = './proc/cerrarsesion.php';
    }
    });
}
