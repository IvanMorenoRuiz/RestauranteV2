function validarUsername(user) {
    if (user === "") { //Aquí valida si el campo del usuario está vacío
        document.getElementById("user").style.borderColor = "red";
        document.getElementById("errorUser").textContent = "El campo del usuario es obligatorio";
    } else if (!user.match(/^(?!.*\s)\b[A-Za-z0-9]{3,}\b/)) { // Aquí valida que el username contenga letras y numeros, no contenga espacios y que tenga como mínimo 3 caracteres
        document.getElementById("user").style.borderColor = "red";
        document.getElementById("errorUser").textContent = "Debes escribir mínimo 3 caracteres";
    } else {
        document.getElementById("errorUser").textContent = " ";
        document.getElementById("user").style.borderColor = "black";
    }
}

function validarPassword(pwd) {
    if (pwd === "") { //Aquí valida si el campo del password está vacío
        document.getElementById("pwd").style.borderColor = "red";
        document.getElementById("errorPwd").textContent = "El campo del password es obligatorio";
    } else if (pwd.length < 5) { //Aquí valida si la longitud del password tiene como mínimo 5 caracteres
        document.getElementById("pwd").style.borderColor = "red";
        document.getElementById("errorPwd").textContent = "Debes escribir mínimo 5 caracteres";
    } else {
        document.getElementById("errorPwd").textContent = " ";
        document.getElementById("pwd").style.borderColor = "black";
    }
}