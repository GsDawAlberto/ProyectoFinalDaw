<?php
use Mediagend\App\Config\Enlaces;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Paciente</title>

    <!-- Estilos propios -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">

</head>
<body>

<div class="container">

    <header>
        <?php include_once Enlaces::LAYOUT_PATH . 'header.php';?>
        <h1>Paciente</h1>
        <p>Acceso al panel de control</p>
    </header>

    <!-- MENSAJE GLOBAL -->
    <div id="formErrorGlobal" class="form-error-global">
        Por favor, verifica tus datos
    </div>

    <form action="<?= Enlaces::BASE_URL ?>paciente/acceder" method="POST" class="form" id="formLoginPaciente">

        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario_paciente" id="usuario_paciente" placeholder="Ingresa tu usuario" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password_paciente" id="password_paciente" placeholder="Ingresa tu contraseña" required>
            <small class="error-msg"></small>
        </div>

        <button type="submit" class="btn-submit">Ingresar</button>

    </form>

    <footer>
        <?php include_once Enlaces::LAYOUT_PATH . 'footer.php'; ?>
    </footer>

</div>

<script>
const form = document.getElementById('formLoginPaciente');
const errorGlobal = document.getElementById('formErrorGlobal');

const usuario = document.getElementById('usuario_paciente');
const password = document.getElementById('password_paciente');

/* =====================
   FUNCIONES UI
===================== */
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

/* =====================
   VALIDACIONES
===================== */
function validarTexto(input, min, max) {
    const value = input.value.trim();
    if (value.length < min || value.length > max) {
        setError(input, `Debe tener entre ${min} y ${max} caracteres`);
        return false;
    }
    setSuccess(input);
    return true;
}

function validarPassword() {
    if (password.value.length < 6) {
        setError(password, 'Mínimo 6 caracteres');
        return false;
    }
    setSuccess(password);
    return true;
}

/* =====================
   VALIDACIÓN EN TIEMPO REAL
===================== */
usuario.addEventListener('input', () => validarTexto(usuario, 3, 15));
password.addEventListener('input', validarPassword);

/* =====================
   ENVÍO DEL FORMULARIO
===================== */
form.addEventListener('submit', e => {
    const valido = validarTexto(usuario, 3, 15) && validarPassword();

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
