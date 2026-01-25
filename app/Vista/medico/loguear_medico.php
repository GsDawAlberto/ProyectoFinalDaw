<?php
use Mediagend\App\Config\Enlaces;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Médico</title>

    <!-- Estilos reutilizados -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
</head>
<body>

<div class="container">

    <header>
        <h2>Registrar Nuevo Médico</h2>
    </header>

    <!-- MENSAJE GLOBAL -->
    <div id="formErrorGlobal" class="form-error-global">
        Por favor, corrige los errores en el formulario
    </div>

    <form action="<?= Enlaces::BASE_URL ?>medico/registrar_medico" method="POST" enctype="multipart/form-data" class="form" id="formRegistroMedico">

        <div class="form-group">
            <label>Foto del Médico</label>
            <input type="file" name="foto_medico" accept="image/*">
        </div>

        <div class="form-group">
            <label>Número de Colegiado</label>
            <input type="text" name="numero_colegiado" id="numero_colegiado" placeholder="Introduce los 9 digitos del colegiado" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Especialidad</label>
            <input type="text" name="especialidad_medico" id="especialidad_medico" placeholder="Introduce la especialidad" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre_medico" id="nombre_medico" placeholder="Introduce el nombre" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Apellidos</label>
            <input type="text" name="apellidos_medico" id="apellidos_medico" placeholder="Introduce los apellidos" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="dni_medico" id="dni_medico" placeholder="00000000A" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono_medico" id="telefono_medico" placeholder="Introduce el teléfono" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email_medico" id="email_medico" placeholder="Introduce el email" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password_medico" id="password_medico" placeholder="******" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Repetir contraseña</label>
            <input type="password" name="password2_medico" id="password2_medico" placeholder="******" required>
            <small class="error-msg"></small>
        </div>

        <button type="reset" class="btn-reset">Borrar todo</button>
        <button type="submit" class="btn-submit">Registrar Médico</button>
    </form>

</div>

<!-- =====================
     VALIDACIÓN EN TIEMPO REAL
===================== -->
<script>
const form = document.getElementById('formRegistroMedico');
const errorGlobal = document.getElementById('formErrorGlobal');

const numeroColegiado = document.getElementById('numero_colegiado');
const especialidad = document.getElementById('especialidad_medico');
const nombre = document.getElementById('nombre_medico');
const apellidos = document.getElementById('apellidos_medico');
const dni = document.getElementById('dni_medico');
const telefono = document.getElementById('telefono_medico');
const email = document.getElementById('email_medico');
const pass1 = document.getElementById('password_medico');
const pass2 = document.getElementById('password2_medico');

/* FUNCIONES DE VALIDACIÓN */
function setError(input, message) {
    const group = input.parentElement;
    group.classList.add('error');
    group.classList.remove('success');
    group.querySelector('.error-msg').innerText = message;
}

function setSuccess(input) {
    const group = input.parentElement;
    group.classList.remove('error');
    group.classList.add('success');
    group.querySelector('.error-msg').innerText = '';
}

function validarTexto(input, min, max) {
    const value = input.value.trim();
    if (value.length < min || value.length > max) {
        setError(input, `Debe tener entre ${min} y ${max} caracteres`);
        return false;
    }
    setSuccess(input);
    return true;
}

function validarNumeroColegiado(input){
    const regex = /^[0-9]{9}$/;
    if (!regex.test(input.value.trim())) {
        setError(input, 'Debe tener 9 dígitos numéricos');
        return false;
    }
    setSuccess(input);
    return true;
}

function validarEmail(input) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regex.test(input.value.trim())) {
        setError(input, 'Email no válido');
        return false;
    }
    setSuccess(input);
    return true;
}

function validarTelefono(input) {
    const regex = /^[0-9]{9}$/;
    if (!regex.test(input.value.trim())) {
        setError(input, 'Debe tener 9 dígitos numéricos');
        return false;
    }
    setSuccess(input);
    return true;
}

function validarPasswords() {
    if (pass1.value.length < 6) {
        setError(pass1, 'Mínimo 6 caracteres');
        return false;
    }
    if (pass1.value !== pass2.value) {
        setError(pass2, 'Las contraseñas no coinciden');
        return false;
    }
    setSuccess(pass1);
    setSuccess(pass2);
    return true;
}

function validarDNI(input) {
    const value = input.value.trim().toUpperCase();
    const dniRegex = /^[0-9]{8}[A-Z]$/;

    if (!dniRegex.test(value)) {
        setError(input, 'DNI inválido, debe contener 9 digitos seguido de una letra');
        return false;
    }

    const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
    const numero = value.substring(0, 8);
    const letra = value.charAt(8);

    if (letras[numero % 23] !== letra) {
        setError(input, 'Letra del DNI incorrecta');
        return false;
    }

    setSuccess(input);
    return true;
}

/* EVENTOS EN TIEMPO REAL */
numeroColegiado.addEventListener('input', () => validarNumeroColegiado(numeroColegiado));
especialidad.addEventListener('input', () => validarTexto(especialidad, 3, 50));
nombre.addEventListener('input', () => validarTexto(nombre, 3, 30));
apellidos.addEventListener('input', () => validarTexto(apellidos, 3, 50));
dni.addEventListener('input', () => validarDNI(dni));
telefono.addEventListener('input', () => validarTelefono(telefono));
email.addEventListener('input', () => validarEmail(email));
pass1.addEventListener('input', validarPasswords);
pass2.addEventListener('input', validarPasswords);

/* VALIDACIÓN AL ENVIAR FORMULARIO */
form.addEventListener('submit', e => {
    const valido =
        validarNumeroColegiado(numeroColegiado) &&
        validarTexto(especialidad, 3, 50) &&
        validarTexto(nombre, 3, 30) &&
        validarTexto(apellidos, 3, 50) &&
        validarDNI(dni) &&
        validarTelefono(telefono) &&
        validarEmail(email) &&
        validarPasswords();

    if (!valido) {
        e.preventDefault();
        errorGlobal.classList.add('visible');
    } else {
        errorGlobal.classList.remove('visible');
    }
});
</script>

</body>
</html>
