<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_paciente" value="<?= $paciente['id_paciente'] ?>">

    <input type="text" name="nombre_paciente" value="<?= $paciente['nombre_paciente'] ?>">
    <input type="text" name="apellidos_paciente" value="<?= $paciente['apellidos_paciente'] ?>">
    <input type="text" name="dni_paciente" value="<?= $paciente['dni_paciente'] ?>">
    <input type="text" name="telefono_paciente" value="<?= $paciente['telefono_paciente'] ?>">
    <input type="email" name="email_paciente" value="<?= $paciente['email_paciente'] ?>">
    <input type="text" name="usuario_paciente" value="<?= $paciente['usuario_paciente'] ?>">

    <input type="file" name="foto_paciente">

    <button type="submit">Guardar cambios</button>
</form>
</body>
</html>