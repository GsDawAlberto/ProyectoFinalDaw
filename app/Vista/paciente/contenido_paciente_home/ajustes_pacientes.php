<?php

use Mediagend\App\Config\Enlaces;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/ajustesPacientes.css">

    <title>Document</title>
</head>

<body>
    <h2>Ajustes de la cuenta</h2>

    <section>
        <fieldset>
            <legend>🧑‍💼 Datos personales</legend>
            <form action="<?= Enlaces::BASE_URL ?>paciente/modificar_mis_datos"
                method="POST"
                enctype="multipart/form-data"
                class="form">
                <!-- Definición de rol de entrada para redirección de pagina, este formulario accede igual que clinica, según el rol volverá a paciente o clinica -->
                <input type="hidden" name="rol" value="paciente">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" value="<?= $_SESSION['paciente']['nombre_paciente'] ?>">
                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" value="<?= $_SESSION['paciente']['apellidos_paciente'] ?>">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?= $_SESSION['paciente']['email_paciente'] ?>">
                <label for="telefono">Teléfono:</label>
                <input type="tel" name="telefono" value="<?= $_SESSION['paciente']['telefono_paciente'] ?>">
                <button>Guardar cambios</button>
            </form>
        </fieldset>
    </section>

    <section>
        <fieldset>
            <legend>🔒 Cambiar contraseña</legend>
            <form action="<?= Enlaces::BASE_URL ?>paciente/modificar_password"
                method="POST"
                enctype="multipart/form-data"
                class="form">
                <label for="password_actual">Contraseña actual:</label>
                <input type="password" name="password_actual" placeholder="Contraseña actual">
                <label for="nueva_password">Nueva contraseña:</label>
                <input type="password" name="nueva_password" placeholder="Nueva contraseña">
                <label for="repetir_nueva_password">Repetir nueva contraseña:</label>
                <input type="password" name="repetir_nueva_password" placeholder="Repetir nueva contraseña">
                <button>Cambiar contraseña</button>
            </form>
        </fieldset>
    </section>

    <section>
        <fieldset>
            <legend>🔔 Preferencias</legend>
            <label><input type="checkbox"> Recibir avisos por email</label>
            <label><input type="checkbox"> Recordatorio 24h antes</label>
        </fieldset>
    </section>

</body>

</html>