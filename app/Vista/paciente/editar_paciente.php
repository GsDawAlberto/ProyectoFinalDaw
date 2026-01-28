<?php
use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Medico;

/* session_start(); */
$clinicaSesion = $_SESSION['clinica']['id_clinica'];

/************************** CONEXIÓN Y MEDICOS ******************************/
$pdo = BaseDatos::getConexion();
$medicoModel = new Medico();

// Para mantener la selección si viene de un GET (id_medico)
$id_medico = filter_input(INPUT_GET, 'id_medico', FILTER_VALIDATE_INT) ?: null;

// Obtener todos los médicos para el select
$medicos = $medicoModel->mostrarMedico($pdo, null); // null para traer todos
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
    <title>Editar Paciente</title>
</head>

<body>

<div class="container">

    <header>
        <h2>Modificar Paciente</h2>
    </header>

    <!-- MENSAJE GLOBAL -->
    <div id="formErrorGlobal" class="form-error-global">
        Por favor, corrige los errores en el formulario
    </div>

    <form action="<?= Enlaces::BASE_URL ?>paciente/modificar"
        method="POST"
        enctype="multipart/form-data"
        class="form"
        id="formEditarPaciente">

        <input type="hidden" name="id_paciente" value="<?= htmlspecialchars($paciente['id_paciente']) ?>">

        <div class="form-group">
            <label>Nombre del Paciente</label>
            <input type="text" name="nombre_paciente" id="nombre_paciente"
                value="<?= htmlspecialchars($paciente['nombre_paciente']) ?>" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Apellidos del Paciente</label>
            <input type="text" name="apellidos_paciente" id="apellidos_paciente"
                value="<?= htmlspecialchars($paciente['apellidos_paciente']) ?>" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="dni_paciente" id="dni_paciente"
                value="<?= htmlspecialchars($paciente['dni_paciente']) ?>" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono_paciente" id="telefono_paciente"
                value="<?= htmlspecialchars($paciente['telefono_paciente']) ?>">
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email_paciente" id="email_paciente"
                value="<?= htmlspecialchars($paciente['email_paciente']) ?>">
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario_paciente" id="usuario_paciente"
                value="<?= htmlspecialchars($paciente['usuario_paciente']) ?>" required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Asignar un Médico (opcional):</label>
            <select name="id_medico">
                <option value="">Ninguno</option>
                <?php foreach ($medicos as $medico): ?>
                    <?php if ($clinicaSesion === (int)$medico['id_clinica']): ?>
                        <option value="<?= $medico['id_medico'] ?>"
                            <?= ($id_medico === (int)$medico['id_medico']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($medico['nombre_medico']) ?> <?= htmlspecialchars($medico['apellidos_medico']) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Foto del Paciente</label>
            <input type="file" name="foto_paciente" accept="image/*">
            <?php if (!empty($paciente['foto_paciente'])): ?>
                <small>Foto actual:</small><br>
                <img src="<?= Enlaces::IMG_PACIENTE_URL . $paciente['foto_paciente'] ?>"
                    alt="Foto actual" width="80" height="80">
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
const form = document.getElementById('formEditarPaciente');
const errorGlobal = document.getElementById('formErrorGlobal');

const nombre = document.getElementById('nombre_paciente');
const apellidos = document.getElementById('apellidos_paciente');
const dni = document.getElementById('dni_paciente');
const telefono = document.getElementById('telefono_paciente');
const email = document.getElementById('email_paciente');
const usuario = document.getElementById('usuario_paciente');

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

function validarTelefono(input) {
    if(input.value.trim() === '') {
        setSuccess(input); // opcional
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
        setSuccess(input); // opcional
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

/* EVENTOS EN TIEMPO REAL */
nombre.addEventListener('input', () => validarTexto(nombre, 3, 30));
apellidos.addEventListener('input', () => validarTexto(apellidos, 3, 50));
dni.addEventListener('input', () => validarDNI(dni));
telefono.addEventListener('input', () => validarTelefono(telefono));
email.addEventListener('input', () => validarEmail(email));
usuario.addEventListener('input', () => validarTexto(usuario, 3, 15));

/* VALIDACIÓN AL ENVIAR FORMULARIO */
form.addEventListener('submit', e => {
    const valido =
        validarTexto(nombre, 3, 30) &&
        validarTexto(apellidos, 3, 50) &&
        validarDNI(dni) &&
        validarTelefono(telefono) &&
        validarEmail(email) &&
        validarTexto(usuario, 3, 15);

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
