<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Administrador</title>
</head>
<body>

    <h2>Registrar Nuevo Administrador</h2>

    <form action="<?= \Mediagend\App\Config\Enlaces::BASE_URL ?>admin/registrar" method="POST">

        <label>Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Usuario:</label>
        <input type="text" name="usuario" required><br><br>

        <label>Contraseña:</label>
        <input type="password" name="password" required><br><br>

        <label>Repetir Contraseña:</label>
        <input type="password" name="password_2" required><br><br>

        <button type="submit">Registrar</button>
    </form>

    <br>
    <a href="<?= \Mediagend\App\Config\Enlaces::BASE_URL ?>admin/login">Volver al login</a>

</body>
</html>
