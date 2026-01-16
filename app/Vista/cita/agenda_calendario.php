<?php

use Mediagend\App\Config\Enlaces;

/* HORAS */

$horas = ['09:00', '10:00', '11:00', '12:00', '13:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];

/* SEMANA (L–V) */
$semana = [];
$dia = new DateTime('monday this week');

for ($i = 0; $i < 5; $i++) {
    $semana[] = (clone $dia)->modify("+$i day");
}

/* CITAS POR FECHA+HORA */
$MapaCitas = [];
foreach ($citas as $cita) {
    $MapaCitas[$cita['fecha_cita']][$cita['hora_cita']] = $cita;
}

/* DÍAS EN ESPAÑOL */
$dias = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agenda Clínica</title>
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/agendaCalendario.css">
</head>

<body>

    <h2>📅 Agenda semanal</h2>

    <table class="agenda">
        <thead>
            <tr>
                <th>Hora</th>
                <?php foreach ($semana as $d): ?>
                    <th>
                        <?= $dias[$d->format('l')] ?><br>
                        <small><?= $d->format('d/m') ?></small>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($horas as $hora): ?>
                <tr>
                    <td class="hora"><?= $hora ?></td>

                    <?php foreach ($semana as $d):
                        $fecha = $d->format('Y-m-d');
                        $cita = $MapaCitas[$fecha][$hora] ?? null;
                    ?>
                        <td class="<?= $cita ? $cita['estado_cita'] : 'libre' ?>">

                            <?php if ($cita): ?>
                                <strong><?= $cita['nombre_medico'] ?></strong><br>
                                <?= $cita['nombre_paciente'] ?? '<em>Hueco libre</em>' ?>
                            <?php else: ?>
                                <form action="<?= Enlaces::BASE_URL ?>citas/form_crear" method="POST">
                                    <input type="hidden" name="fecha" value="<?= $fecha ?>">
                                    <input type="hidden" name="hora" value="<?= $hora ?>">
                                    <button class="btn-hueco">＋</button>
                                </form>
                            <?php endif; ?>

                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>