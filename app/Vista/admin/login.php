<?php
use Mediagend\App\Config\Enlaces;
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador</title>
</head>
<body>

    <h2>Acceso Administrador</h2>

    <form action="<?= Enlaces::BASE_URL ?>admin/acceder" method="POST">

        <label>Usuario:</label>
        <input type="text" name="usuario" required><br><br>

        <label>Contraseña:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Ingresar</button>
    </form>

    <br>
    <a href="<?=Enlaces::BASE_URL ?>admin/loguear">Crear cuenta</a>

</body>
</html>
