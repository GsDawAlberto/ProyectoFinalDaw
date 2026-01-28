<?php
use Mediagend\App\Config\Enlaces;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Médico</title>

    <!-- Estilos propios -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
</head>
<body>

<div class="container">

    <header>
        <?php include_once Enlaces::LAYOUT_PATH . 'header.php';?>
        <h1>Médico</h1>
        <p>Acceso al panel de control</p>
    </header>

    <!-- MENSAJE GLOBAL -->
    <div id="formErrorGlobal" class="form-error-global">
        Todos los campos son obligatorios y deben ser válidos
    </div>

    <form action="<?= Enlaces::BASE_URL ?>medico/acceder" method="POST" class="form" id="formLoginMedico">

        <div class="form-group">
            <label>Número de Colegiado</label>
            <input type="text" name="numero_colegiado" id="numero_colegiado" placeholder="Introduce los 9 digitos de numero de colegiado" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password_medico" id="password" placeholder="Ingresa tu contraseña" required>
            <span id="ver_pass_1">Mostrar</span>
            <small class="error-msg"></small>
        </div>

        <button type="submit" class="btn-submit">Ingresar</button>

    </form>

    <footer>
        <?php include_once Enlaces::LAYOUT_PATH . 'footer.php'; ?>
    </footer>

</div>

<!-- =====================
     MOSTRAR CONTRASEÑA
===================== -->
    <script>
        function togglePass(inputId, btnId) {
            const input = document.getElementById(inputId);
            const btn = document.getElementById(btnId);

            btn.addEventListener("click", () => {
                if (input.type === "password") {
                    input.type = "text";
                    btn.textContent = "Ocultar";
                } else {
                    input.type = "password";
                    btn.textContent = "Mostrar";
                }
            });
        }

        togglePass("password", "ver_pass_1");
    </script>

<!-- =====================
     VALIDACIÓN EN TIEMPO REAL
===================== -->
<script>
const form = document.getElementById('formLoginMedico');
const errorGlobal = document.getElementById('formErrorGlobal');

const numeroColegiado = document.getElementById('numero_colegiado');
const password = document.getElementById('password');

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

function validarNumeroColegiado(input){
    const regex = /^[0-9]{9}$/;
    if (!regex.test(input.value.trim())) {
        setError(input, 'Debe tener 9 dígitos numéricos');
        return false;
    }
    setSuccess(input);
    return true;
}

function validarPassword(input) {
    if (input.value.length < 6) {
        setError(input, 'Mínimo 6 caracteres');
        return false;
    }
    setSuccess(input);
    return true;
}

/* VALIDACIÓN EN TIEMPO REAL */
numeroColegiado.addEventListener('input', () => validarNumeroColegiado(numeroColegiado));
password.addEventListener('input', () => validarPassword(password));

/* VALIDACIÓN AL ENVIAR FORMULARIO */
form.addEventListener('submit', e => {
    const valido =
        validarNumeroColegiado(numeroColegiado) &&
        validarPassword(password);

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
