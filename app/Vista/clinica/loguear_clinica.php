<?php

use Mediagend\App\Config\Enlaces;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombreAdmin = $_SESSION['admin']['usuario_admin'] ?? 'admin_default';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Clínica</title>

    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
</head>

<body>

    <div class="container">

        <header>
            <h2>Registrar Nueva Clínica</h2>
        </header>

        <!-- MENSAJE GLOBAL -->
        <div id="formErrorGlobal" class="form-error-global">
            Todas las entradas deben ser validadas
        </div>

        <form action="<?= Enlaces::BASE_URL ?>clinica/registrar_clinica"
            method="POST"
            enctype="multipart/form-data"
            class="form"
            id="formClinica">

            <input type="hidden" name="usuario_admin_clinica" value="<?= htmlspecialchars($nombreAdmin) ?>">

            <div class="form-group">
                <label>Foto de la clínica</label>
                <input type="file" name="foto_clinica" accept="image/*">
            </div>

            <div class="form-group">
                <label>Nombre de la clínica</label>
                <input type="text" name="nombre_clinica" id="nombre_clinica" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>NIF (DNI o CIF)</label>
                <input type="text"
                    name="nif_clinica"
                    id="nif_clinica"
                    placeholder="12345678Z o A12345678"
                    required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion_clinica" id="direccion_clinica" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono_clinica" id="telefono_clinica" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email_clinica" id="email_clinica" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="usuario_clinica" id="usuario_clinica" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group" id="box_pass">
                <label>Contraseña:</label>
                <input type="password" name="password" id="password" placeholder="Ingresa una contraseña" required>
                <span id="ver_pass_1">Mostrar</span>
                <small class="error-msg"></small>
            </div>

            <div class="form-group" id="box_pass">
                <label>Repetir Contraseña:</label>
                <input type="password" name="password_2" id="password_2" placeholder="Repite la contraseña" required>
                <span id="ver_pass_2">Mostrar</span>
                <small class="error-msg"></small>
            </div>
            <button type="reset" class="btn-reset">Borrar todo</button>
            <button type="submit" class="btn-submit">Registrar Clínica</button>
        </form>

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
        togglePass("password_2", "ver_pass_2");
    </script>
    <!-- =====================
     VALIDACIÓN EN TIEMPO REAL
===================== -->
    <script>
        const form = document.getElementById('formClinica');
        const errorGlobal = document.getElementById('formErrorGlobal');

        const nombre = document.getElementById('nombre_clinica');
        const nif = document.getElementById('nif_clinica');
        const direccion = document.getElementById('direccion_clinica');
        const telefono = document.getElementById('telefono_clinica');
        const email = document.getElementById('email_clinica');
        const usuario = document.getElementById('usuario_clinica');
        const pass1 = document.getElementById('password');
        const pass2 = document.getElementById('password_2');

        /* =====================
           HELPERS UI
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
            const regex = /^[0-9]{9}$/;
            if (!regex.test(input.value.trim())) {
                setError(input, 'Debe tener 9 dígitos');
                return false;
            }
            setSuccess(input);
            return true;
        }

        /* DNI o CIF */
        function validarNIF(input) {
            const value = input.value.trim().toUpperCase();

            const dni = /^[0-9]{8}[A-Z]$/;
            const cif = /^[A-Z][0-9]{8}$/;

            if (!dni.test(value) && !cif.test(value)) {
                setError(input, 'Debe ser un DNI o CIF válido');
                return false;
            }

            setSuccess(input);
            return true;
        }

        function validarPasswords() {
            let valido = true;

            // Validar longitud pass1
            if (pass1.value.length < 6) {
                setError(pass1, 'Mínimo 6 caracteres');
                valido = false;
            } else {
                setSuccess(pass1);
            }

            // Solo validar coincidencia si pass2 tiene algo escrito
            if (pass2.value.length > 0) {
                if (pass1.value !== pass2.value) {
                    setError(pass2, 'Las contraseñas no coinciden');
                    valido = false;
                } else {
                    setSuccess(pass2);
                }
            } else {
                // si aún no escribió, no muestres error
                setSuccess(pass2);
            }

            return valido;
        }

        /* =====================
           TIEMPO REAL
        ===================== */
        nombre.addEventListener('input', () => validarTexto(nombre, 3, 30));
        nif.addEventListener('input', () => validarNIF(nif));
        direccion.addEventListener('input', () => validarTexto(direccion, 5, 100));
        telefono.addEventListener('input', () => validarTelefono(telefono));
        email.addEventListener('input', () => validarEmail(email));
        usuario.addEventListener('input', () => validarTexto(usuario, 3, 15));
        pass1.addEventListener('input', validarPassword);
        pass2.addEventListener('input', validarPassword);

        /* =====================
           SUBMIT
        ===================== */
        form.addEventListener('submit', e => {
            const valido =
                validarTexto(nombre, 3, 30) &&
                validarNIF(nif) &&
                validarTexto(direccion, 5, 100) &&
                validarTelefono(telefono) &&
                validarEmail(email) &&
                validarTexto(usuario, 3, 15) &&
                validarPassword();

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