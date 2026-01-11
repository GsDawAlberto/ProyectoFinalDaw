<?php

use Mediagend\App\Config\Enlaces;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Estilos reutilizados -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
    <title>Editar Paciente</title>
</head>

<body>
    <form action="<?= Enlaces::BASE_URL ?>paciente/modificar"
        method="POST"
        enctype="multipart/form-data"
        class="form">

        <input type="hidden" name="id_paciente" value="<?= $paciente['id_paciente'] ?>">

        <div class="form-group">
            <label>Nombre del Paciente</label>
            <input type="text" name="nombre_paciente"
                value="<?= htmlspecialchars($paciente['nombre_paciente']) ?>" required>
        </div>

        <div class="form-group">
            <label>Apellidos del Paciente</label>
            <input type="text" name="apellidos_paciente"
                value="<?= htmlspecialchars($paciente['apellidos_paciente']) ?>" required>
        </div>

        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="dni_paciente"
                value="<?= htmlspecialchars($paciente['dni_paciente']) ?>" required>
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono_paciente"
                value="<?= htmlspecialchars($paciente['telefono_paciente']) ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email_paciente"
                value="<?= htmlspecialchars($paciente['email_paciente']) ?>" required>
        </div>

        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario_paciente"
                value="<?= htmlspecialchars($paciente['usuario_paciente']) ?>" required>
        </div>

        <div class="form-group">
            <label>Foto del Paciente</label>
            <input type="file" name="foto_paciente" accept="image/*">

            <?php if (!empty($paciente['foto_paciente'])): ?>
                <small>Foto actual:</small><br>
                <img src="<?= Enlaces::IMG_PACIENTE_URL . $paciente['foto_paciente'] ?>"
                    alt="Foto actual"
                    width="80" height="80">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">
            Guardar cambios
        </button>

    </form>
</body>

</html>