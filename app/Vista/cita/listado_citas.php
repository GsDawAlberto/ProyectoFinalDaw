<?php
use Mediagend\App\Config\Enlaces;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda de Citas</title>
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/listadoCitas.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
</head>

<body>

<div class="container">

    <h2>📅 Agenda de la Clínica</h2>

    <?php if (empty($citas)): ?>
        <div class="no-citas">
            No hay citas registradas actualmente.
        </div>
    <?php else: ?>

        <table class="tabla-citas">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Médico</th>
                    <th>Paciente</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($citas as $cita): ?>
                <tr class="estado-<?= $cita['estado_cita'] ?>">

                    <td><?= htmlspecialchars($cita['fecha_cita']) ?></td>
                    <td><?= htmlspecialchars(substr($cita['hora_cita'], 0, 5)) ?></td>

                    <td>
                        <?= htmlspecialchars($cita['nombre_medico'] ?? '—') ?>
                    </td>

                    <td>
                        <?php if (!empty($cita['id_paciente'])): ?>
                            <?= htmlspecialchars($cita['nombre_paciente'] ?? 'Asignado') ?>
                        <?php else: ?>
                            <span class="libre">Hueco libre</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <span class="badge <?= $cita['estado_cita'] ?>">
                            <?= ucfirst($cita['estado_cita']) ?>
                        </span>
                    </td>

                    <td class="acciones">

                        <?php if (empty($cita['id_paciente'])): ?>
                            <form action="<?= Enlaces::BASE_URL ?>citas/asignar" method="POST">
                                <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                <button class="btn asignar">➕ Asignar</button>
                            </form>
                        <?php endif; ?>

                        <?php if ($cita['estado_cita'] === 'pendiente'): ?>
                            <form action="<?= Enlaces::BASE_URL ?>citas/confirmar" method="POST">
                                <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                <button class="btn confirmar">✔ Confirmar</button>
                            </form>
                        <?php endif; ?>

                        <form action="<?= Enlaces::BASE_URL ?>citas/cancelar" method="POST"
                              onsubmit="return confirm('¿Cancelar esta cita?');">
                            <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                            <button class="btn cancelar">✖ Cancelar</button>
                        </form>

                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>

    <?php endif; ?>

</div>

</body>
</html>