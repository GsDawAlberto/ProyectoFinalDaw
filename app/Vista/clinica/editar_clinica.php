<?php
use Mediagend\App\Config\Enlaces;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
    <title>Editar Clínica</title>
</head>

<body>
<div class="container">

    <header>
        <h2>Editar Información de la Clínica</h2>
    </header>

    <!-- MENSAJE GLOBAL -->
    <div id="formErrorGlobal" class="form-error-global">
        Todas las entradas deben ser validadas
    </div>

    <form action="<?= Enlaces::BASE_URL ?>clinica/modificar"
          method="POST"
          enctype="multipart/form-data"
          class="form"
          id="formEditarClinica">

        <!-- ID oculto -->
        <input type="hidden" name="id_clinica" value="<?= htmlspecialchars($clinica['id_clinica']) ?>">
        <!-- Usuario admin oculto -->
        <input type="hidden" name="usuario_admin_clinica"
               value="<?= htmlspecialchars($clinica['usuario_admin_clinica'] ?? $_SESSION['admin']['usuario_admin'] ?? '') ?>">

        <div class="form-group">
            <label>Nombre de la Clínica</label>
            <input type="text"
                   name="nombre_clinica"
                   id="nombre_clinica"
                   value="<?= htmlspecialchars($clinica['nombre_clinica']) ?>"
                   required>
            <small class="error-msg"></small>
        </div>

        <!-- NIF -->
        <div class="form-group">
            <label>NIF (DNI o CIF)</label>
            <input type="text"
                   name="nif_clinica"
                   id="nif_clinica"
                   value="<?= htmlspecialchars($clinica['nif_clinica'] ?? '') ?>"
                   placeholder="00000000A o A00000000"
                   required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text"
                   name="telefono_clinica"
                   id="telefono_clinica"
                   value="<?= htmlspecialchars($clinica['telefono_clinica']) ?>">
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email"
                   name="email_clinica"
                   id="email_clinica"
                   value="<?= htmlspecialchars($clinica['email_clinica']) ?>">
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text"
                   name="direccion_clinica"
                   id="direccion_clinica"
                   value="<?= htmlspecialchars($clinica['direccion_clinica']) ?>">
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Usuario</label>
            <input type="text"
                   name="usuario_clinica"
                   id="usuario_clinica"
                   value="<?= htmlspecialchars($clinica['usuario_clinica']) ?>"
                   required>
            <small class="error-msg"></small>
        </div>

        <div class="form-group">
            <label>Logo de la Clínica</label>
            <input type="file" name="foto_clinica" accept="image/*">
            <?php if (!empty($clinica['foto_clinica'])): ?>
                <small>Logo actual:</small><br>
                <img src="<?= Enlaces::LOGOS_URL . $clinica['foto_clinica'] ?>"
                     alt="Foto actual" width="80" height="80">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">Guardar cambios</button>
    </form>
</div>

<!-- =====================
     VALIDACIÓN EN TIEMPO REAL
===================== -->
<script>
const form = document.getElementById('formEditarClinica');
const errorGlobal = document.getElementById('formErrorGlobal');

const nombre = document.getElementById('nombre_clinica');
const nif = document.getElementById('nif_clinica');
const telefono = document.getElementById('telefono_clinica');
const email = document.getElementById('email_clinica');
const direccion = document.getElementById('direccion_clinica');
const usuario = document.getElementById('usuario_clinica');

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
    if (input.value.trim() === '') {
        setSuccess(input);
        return true;
    }
    if (!/^[0-9]{9}$/.test(input.value.trim())) {
        setError(input, 'Debe tener 9 dígitos numéricos');
        return false;
    }
    setSuccess(input);
    return true;
}

function validarNIF(input) {
    const value = input.value.trim().toUpperCase();
    const dniRegex = /^[0-9]{8}[A-Z]$/;
    const cifRegex = /^[A-Z][0-9]{8}$/;

    if (!dniRegex.test(value) && !cifRegex.test(value)) {
        setError(input, 'Debe ser un DNI o CIF válido');
        return false;
    }

    // Validación letra DNI
    if (dniRegex.test(value)) {
        const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        const numero = value.substring(0, 8);
        const letra = value.charAt(8);

        if (letras[numero % 23] !== letra) {
            setError(input, 'Letra del DNI incorrecta');
            return false;
        }
    }

    setSuccess(input);
    return true;
}

/* =====================
   TIEMPO REAL
===================== */
nombre.addEventListener('input', () => validarTexto(nombre, 3, 50));
nif.addEventListener('input', () => validarNIF(nif));
direccion.addEventListener('input', () => validarTexto(direccion, 5, 100));
telefono.addEventListener('input', () => validarTelefono(telefono));
email.addEventListener('input', () => validarEmail(email));
usuario.addEventListener('input', () => validarTexto(usuario, 3, 15));

/* =====================
   ENVÍO
===================== */
form.addEventListener('submit', e => {
    const valido =
        validarTexto(nombre, 3, 50) &&
        validarNIF(nif) &&
        validarTexto(direccion, 5, 100) &&
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
