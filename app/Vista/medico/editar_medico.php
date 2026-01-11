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

    <title>Editar Médico</title>
</head>

<body>

    <form action="<?= Enlaces::BASE_URL ?>medico/modificar"
        method="POST"
        enctype="multipart/form-data"
        class="form">

        <!-- ID oculto -->
        <input type="hidden" name="id_medico" value="<?= $medico['id_medico'] ?>">

        <div class="form-group">
            <label>Nombre del Médico</label>
            <input type="text"
                name="nombre_medico"
                value="<?= htmlspecialchars($medico['nombre_medico']) ?>"
                required>
        </div>

        <div class="form-group">
            <label>Apellidos del Médico</label>
            <input type="text"
                name="apellidos_medico"
                value="<?= htmlspecialchars($medico['apellidos_medico']) ?>"
                required>
        </div>

        <div class="form-group">
            <label>Número de colegiado</label>
            <input type="text"
                name="numero_colegiado"
                value="<?= htmlspecialchars($medico['numero_colegiado']) ?>"
                required>
        </div>

        <div class="form-group">
            <label>Especialidad</label>
            <input type="text"
                name="especialidad_medico"
                value="<?= htmlspecialchars($medico['especialidad_medico']) ?>">
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text"
                name="telefono_medico"
                value="<?= htmlspecialchars($medico['telefono_medico']) ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email"
                name="email_medico"
                value="<?= htmlspecialchars($medico['email_medico']) ?>">
        </div>

        <div class="form-group">
            <label>Foto del Médico</label>
            <input type="file" name="foto_medico" accept="image/*">

            <?php if (!empty($medico['foto_medico'])): ?>
                <small>Foto actual:</small><br>
                <img src="<?= Enlaces::IMG_MEDICO_URL . $medico['foto_medico'] ?>"
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