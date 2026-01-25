<?php

use Mediagend\App\Config\Enlaces;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Paciente</title>

    <!-- Estilos reutilizados -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
</head>

<body>

    <div class="container">

        <header>
            <h2>Registrar Nuevo Paciente</h2>
        </header>

        <!-- MENSAJE GLOBAL -->
        <div id="formErrorGlobal" class="form-error-global">
            Todas las entradas deben ser validadas
        </div>

        <form action="<?= Enlaces::BASE_URL ?>paciente/registrar_paciente"
            method="POST"
            enctype="multipart/form-data"
            class="form"
            id="formPaciente">

            <div class="form-group">
                <label>Nombre del Paciente</label>
                <input type="text" name="nombre_paciente" id="nombre_paciente" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Apellidos Paciente</label>
                <input type="text" name="apellidos_paciente" id="apellidos_paciente" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>DNI Paciente</label>
                <input type="text" name="dni_paciente" id="dni_paciente" placeholder="00000000A" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Foto del Paciente</label>
                <input type="file" name="foto_paciente" accept="image/*">
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono_paciente" id="telefono_paciente" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email_paciente" id="email_paciente" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="usuario_paciente" id="usuario_paciente" required>
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
            <button type="submit" class="btn-submit">Registrar Paciente</button>
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

    <script>
        const form = document.getElementById('formPaciente');
        const errorGlobal = document.getElementById('formErrorGlobal');

        const nombre = document.getElementById('nombre_paciente');
        const apellidos = document.getElementById('apellidos_paciente');
        const dni = document.getElementById('dni_paciente');
        const telefono = document.getElementById('telefono_paciente');
        const email = document.getElementById('email_paciente');
        const usuario = document.getElementById('usuario_paciente');
        const pass1 = document.getElementById('password_paciente');
        const pass2 = document.getElementById('password2_paciente');

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
            const regex = /^[0-9]{9}$/;
            if (!regex.test(input.value.trim())) {
                setError(input, 'Debe tener 9 dígitos numéricos');
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

        /* =====================
           TIEMPO REAL
        ===================== */
        nombre.addEventListener('input', () => validarTexto(nombre, 3, 30));
        apellidos.addEventListener('input', () => validarTexto(apellidos, 3, 50));
        dni.addEventListener('input', () => validarDNI(dni));
        telefono.addEventListener('input', () => validarTelefono(telefono));
        email.addEventListener('input', () => validarEmail(email));
        usuario.addEventListener('input', () => validarTexto(usuario, 3, 15));
        pass1.addEventListener('input', validarPassword);
        pass2.addEventListener('input', validarPassword);

        /* =====================
           ENVÍO
        ===================== */
        form.addEventListener('submit', e => {
            const valido =
                validarTexto(nombre, 3, 30) &&
                validarTexto(apellidos, 3, 50) &&
                validarDNI(dni) &&
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