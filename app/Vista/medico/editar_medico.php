<?php
use Mediagend\App\Config\Enlaces;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Estilos reutilizados -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">

    <title>Editar Médico</title>
</head>

<body>

<div class="container">

    <header>
        <h2>Editar Médico</h2>
    </header>

    <!-- FORMULARIO -->
    <form action="<?= Enlaces::BASE_URL ?>medico/modificar"
        method="POST"
        enctype="multipart/form-data"
        class="form"
        id="formEditarMedico">

        <!-- ID oculto -->
        <input type="hidden" name="id_medico" value="<?= htmlspecialchars($medico['id_medico']) ?>">

        <div class="form-group">
            <label>Nombre del Médico</label>
            <input type="text"
                name="nombre_medico"
                id="nombre_medico"
                value="<?= htmlspecialchars($medico['nombre_medico']) ?>"
                required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Apellidos del Médico</label>
            <input type="text"
                name="apellidos_medico"
                id="apellidos_medico"
                value="<?= htmlspecialchars($medico['apellidos_medico']) ?>"
                required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Número de colegiado</label>
            <input type="text"
                name="numero_colegiado"
                id="numero_colegiado"
                value="<?= htmlspecialchars($medico['numero_colegiado']) ?>"
                required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Especialidad</label>
            <input type="text"
                name="especialidad_medico"
                id="especialidad_medico"
                value="<?= htmlspecialchars($medico['especialidad_medico']) ?>">
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text"
                name="telefono_medico"
                id="telefono_medico"
                value="<?= htmlspecialchars($medico['telefono_medico']) ?>">
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email"
                name="email_medico"
                id="email_medico"
                value="<?= htmlspecialchars($medico['email_medico']) ?>">
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Foto del Médico</label>
            <input type="file" name="foto_medico" accept="image/*">
            <?php if (!empty($medico['foto_medico'])): ?>
                <small>Foto actual:</small><br>
                <img src="<?= Enlaces::IMG_MEDICO_URL . $medico['foto_medico'] ?>"
                    alt="Foto actual"
                    width="80" height="80">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">
            Guardar cambios
        </button>

    </form>
</div>

<!-- =====================
     VALIDACIÓN EN TIEMPO REAL
===================== -->
<script>
const form = document.getElementById('formEditarMedico');
const errorGlobal = document.createElement('div');
errorGlobal.id = 'formErrorGlobal';
errorGlobal.className = 'form-error-global';
errorGlobal.innerText = 'Por favor, corrige los errores en el formulario';
form.prepend(errorGlobal);

// Campos
const nombre = document.getElementById('nombre_medico');
const apellidos = document.getElementById('apellidos_medico');
const numeroColegiado = document.getElementById('numero_colegiado');
const especialidad = document.getElementById('especialidad_medico');
const telefono = document.getElementById('telefono_medico');
const email = document.getElementById('email_medico');

// Funciones de validación
function setError(input, message) {
    const group = input.parentElement;
    group.classList.add('error');
    group.classList.remove('success');
    let small = group.querySelector('.error-msg');
    if (!small) {
        small = document.createElement('small');
        small.className = 'error-msg';
        group.appendChild(small);
    }
    small.innerText = message;
}

function setSuccess(input) {
    const group = input.parentElement;
    group.classList.remove('error');
    group.classList.add('success');
    const small = group.querySelector('.error-msg');
    if (small) small.innerText = '';
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

function validarTelefono(input) {
    if(input.value.trim() === '') {
        setSuccess(input); // campo opcional
        return true;
    }
    const regex = /^[0-9]{9}$/;
    if (!regex.test(input.value.trim())) {
        setError(input, 'Debe tener 9 dígitos numéricos');
        return false;
    }
    setSuccess(input);
    return true;
}

function validarEmail(input) {
    if(input.value.trim() === '') {
        setSuccess(input); // campo opcional
        return true;
    }
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regex.test(input.value.trim())) {
        setError(input, 'Email no válido');
        return false;
    }
    setSuccess(input);
    return true;
}

// Validación en tiempo real
nombre.addEventListener('input', () => validarTexto(nombre, 3, 30));
apellidos.addEventListener('input', () => validarTexto(apellidos, 3, 50));
numeroColegiado.addEventListener('input', () => validarNumeroColegiado(numeroColegiado));
especialidad.addEventListener('input', () => {
    if (especialidad.value.trim() !== '') {
        validarTexto(especialidad, 3, 50);
    } else {
        setSuccess(especialidad);
    }
});
telefono.addEventListener('input', () => validarTelefono(telefono));
email.addEventListener('input', () => validarEmail(email));

// Validación al enviar formulario
form.addEventListener('submit', e => {
    const valido =
        validarTexto(nombre, 3, 30) &&
        validarTexto(apellidos, 3, 50) &&
        validarNumeroColegiado(numeroColegiado) &&
        (especialidad.value.trim() === '' || validarTexto(especialidad, 3, 50)) &&
        validarTelefono(telefono) &&
        validarEmail(email);

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
