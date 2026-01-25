<?php

use Mediagend\App\Config\Enlaces;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/ajustesPacientes.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">

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

                <input type="hidden" name="id_paciente" value="<?= $paciente['id_paciente'] ?>">
                
                <p>Nombre: <?= htmlspecialchars($_SESSION['paciente']['nombre_paciente']) ?></p>
                <p>Apellidos: <?= htmlspecialchars($_SESSION['paciente']['apellidos_paciente']) ?></p>

                <!-- Email -->
                <label for="email_paciente">Email:</label>
                <input type="email"
                    id="email_paciente"
                    name="email_paciente"
                    value="<?= htmlspecialchars($_SESSION['paciente']['email_paciente']) ?>"
                    placeholder="ejemplo@correo.com"
                    required
                    maxlength="100">

                <!-- Teléfono -->
                <label for="telefono_paciente">Teléfono:</label>
                <input type="tel"
                    id="telefono_paciente"
                    name="telefono_paciente"
                    value="<?= htmlspecialchars($_SESSION['paciente']['telefono_paciente']) ?>"
                    placeholder="123456789"
                    pattern="[0-9]{9}"
                    title="Introduce 9 dígitos"
                    required>

                <button type="submit">Guardar cambios</button>
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