<?php

use Mediagend\App\Config\Enlaces;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/editarCita.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
    <title>Document</title>
</head>

<body>

    <h2>Editar Cita</h2>
    <form action="<?= Enlaces::BASE_URL ?>citas/modificar" method="POST">
        <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">

        <label>Fecha</label>
        <input type="hidden" name="fecha" value="<?= $cita['fecha_cita'] ?>">
        <label for=""><?= $cita['fecha_cita'] ?></label>

        <label>Hora</label>
        <input type="hidden" name="hora" value="<?= substr($cita['hora_cita'],0,5) ?>">
        <label for=""><?= substr($cita['hora_cita'],0,5) ?></label>

        <label>Estado</label>
        <select name="estado">
            <option value="pendiente" <?= $cita['estado_cita'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="confirmada" <?= $cita['estado_cita'] == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
            <option value="realizada" <?= $cita['estado_cita'] == 'realizada' ? 'selected' : '' ?>>Realizada</option>
            <option value="no_asiste" <?= $cita['estado_cita'] == 'no_asiste' ? 'selected' : '' ?>>No asiste</option>
        </select>

        <label>Motivo</label>
        <textarea name="motivo"><?= htmlspecialchars($cita['motivo_cita'] ?? '') ?></textarea>

        <button type="submit">💾 Guardar cambios</button>
    </form>

    <h2>Eliminar Cita</h2>
    <form action="<?= Enlaces::BASE_URL ?>citas/eliminar" method="POST"
          onsubmit="return confirm('¿Seguro que deseas ⚠️ ELIMINAR ⚠️ esta cita: <?= $cita['id_cita'] ?>?');">
        <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
        <button type="submit" class="btn-eliminar">🗑️ Eliminar</button>
    </form>

</body>

</html>