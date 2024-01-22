function validarFormulario() {
    // Validar nombre
    var nombre = document.forms["myForm"]["nombre"].value;
    if (!/^[a-zA-Z]+$/.test(nombre)) {
        alert("El nombre solo debe contener letras.");
        return false;
    }

    // Validar apellidos
    var apellidos = document.forms["myForm"]["apellidos"].value;
    if (!/^[a-zA-Z ]+$/.test(apellidos)) {
        alert("Los apellidos solo deben contener letras y espacios.");
        return false;
    }

    // Validar contraseña
    var contraseña = document.forms["myForm"]["password"].value;
    if (contraseña.length < 5) {
        alert("La contraseña debe tener al menos 5 caracteres.");
        return false;
    }

    return true;
}
