<?php

use Mediagend\App\Config\Enlaces;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Administrador</title>

    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Solo_logo.png">
</head>

<body>

    <div class="container">

        <header>
            <h2>Registrar Nuevo Administrador</h2>
        </header>

        <!-- MENSAJE GLOBAL -->
        <div id="formErrorGlobal" class="form-error-global">
            No se pudo enviar el formulario. Por favor, verifique los errores e inténtalo de nuevo.
        </div>

        <form action="<?= Enlaces::BASE_URL ?>admin/registrar" method="POST" class="form" id="formAdmin">

            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre_admin" id="nombre" placeholder="Ingresa tu nombre" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Apellidos:</label>
                <input type="text" name="apellidos_admin" id="apellidos" placeholder="Ingresa tus apellidos" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>DNI:</label>
                <input type="text" name="dni_admin" id="dni" placeholder="00000000A" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email_admin" id="email" placeholder="Ingresa tu email" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" name="usuario_admin" id="usuario" placeholder="Ingresa tu usuario" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" id="password" placeholder="Ingresa una contraseña" required>
                <small class="error-msg"></small>
            </div>

            <div class="form-group">
                <label>Repetir Contraseña:</label>
                <input type="password" name="password_2" id="password_2" placeholder="Repite la contraseña" required>
                <small class="error-msg"></small>
            </div>
            <button type="reset" class="btn-reset">Borrar todo</button>
            <button type="submit" class="btn-submit">Registrar Administrador</button>

        </form>

        <div class="extra-links">
            <p>¿Ya estás registrado?</p>
            <h3><a href="<?= Enlaces::BASE_URL ?>admin/login_admin">Volver al login</a></h3>
        </div>

        <footer>
            <?php include_once Enlaces::LAYOUT_PATH . 'footer.php'; ?>
        </footer>

    </div>

    <!-- =====================
     VALIDACIÓN EN TIEMPO REAL
===================== -->
    <script>
        const form = document.getElementById('formAdmin');
        const errorGlobal = document.getElementById('formErrorGlobal');

        const nombre = document.getElementById('nombre');
        const apellidos = document.getElementById('apellidos');
        const dni = document.getElementById('dni');
        const email = document.getElementById('email');
        const usuario = document.getElementById('usuario');
        const pass1 = document.getElementById('password');
        const pass2 = document.getElementById('password_2');

        /* =====================
           UI HELPERS
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

        function validarDNI(input) {
            const dniValue = input.value.trim().toUpperCase();
            const regex = /^\d{8}[A-Z]$/;

            if (!regex.test(dniValue)) {
                setError(input, 'Formato inválido (00000000A)');
                return false;
            }

            const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
            const numero = dniValue.substring(0, 8);
            const letra = dniValue.charAt(8);

            if (letras[numero % 23] !== letra) {
                setError(input, 'Letra del DNI no válida');
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

        /* =====================
           TIEMPO REAL
        ===================== */
        nombre.addEventListener('input', () => validarTexto(nombre, 3, 20));
        apellidos.addEventListener('input', () => validarTexto(apellidos, 10, 50));
        usuario.addEventListener('input', () => validarTexto(usuario, 3, 15));
        dni.addEventListener('input', () => validarDNI(dni));
        email.addEventListener('input', () => validarEmail(email));
        pass1.addEventListener('input', validarPasswords);
        pass2.addEventListener('input', validarPasswords);

        /* =====================
           SUBMIT
        ===================== */
        form.addEventListener('submit', e => {

            const valido =
                validarTexto(nombre, 3, 20) &&
                validarTexto(apellidos, 3, 40) &&
                validarTexto(usuario, 3, 15) &&
                validarDNI(dni) &&
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