<?php

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Cita;
use Mediagend\App\Modelo\Medico;

if (!isset($_SESSION['clinica'])) {
    exit('Acceso denegado');
}

$pdo = BaseDatos::getConexion();

/* MODELOS */
$citaModel   = new Cita();
$medicoModel = new Medico();

/* DATOS */
$mostrarCita = $citaModel->mostrarPorClinica(
    $pdo,
    $_SESSION['clinica']['id_clinica']
);

$medicos = $medicoModel->listarPorClinica(
    $pdo,
    $_SESSION['clinica']['id_clinica']
);

/* HORAS */
$horas = [
    '09:00','10:00','11:00','12:00','13:00',
    '15:00','16:00','17:00','18:00','19:00','20:00'
];

/* SEMANA (L–V) */
$semana = [];
$dia = new DateTime('monday this week');
for ($i = 0; $i < 5; $i++) {
    $semana[] = (clone $dia)->modify("+$i day");
}

/* DÍAS EN ESPAÑOL */
$dias = [
    'Monday'    => 'Lunes',
    'Tuesday'   => 'Martes',
    'Wednesday' => 'Miércoles',
    'Thursday'  => 'Jueves',
    'Friday'    => 'Viernes'
];

/* INDEXAR CITAS POR fecha → hora → medico */
$citasIndexadas = [];

foreach ($mostrarCita as $c) {
    $fecha = $c['fecha_cita'];
    $hora  = substr($c['hora_cita'], 0, 5);
    $idMed = $c['id_medico'];

    $citasIndexadas[$fecha][$hora][$idMed] = $c;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda Clínica</title>
    <link rel="stylesheet" href="<?= Enlaces::STYLES_URL ?>agenda_calendario.css">
</head>

<body>

<h2>📅 Agenda semanal de la clínica</h2>
<p>🟡 Pendiente · 🔵 Confirmada · 🟢 Realizada · 🔴 Cancelada</p>

<div class="agenda-wrapper">
<table class="agenda">

    <!-- CABECERA -->
    <thead>
        <tr>
            <th>Hora</th>

            <?php foreach ($semana as $d): ?>
                <?php foreach ($medicos as $m): ?>
                    <th>
                        <?= $dias[$d->format('l')] ?><br>
                        <small><?= $d->format('d/m') ?></small><br>
                        <strong><?= htmlspecialchars($m['nombre_medico']) ?></strong>
                    </th>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tr>
    </thead>

    <!-- CUERPO -->
    <tbody>
        <?php foreach ($horas as $hora): ?>
            <tr>
                <td class="hora"><?= $hora ?></td>

                <?php foreach ($semana as $d): ?>
                    <?php foreach ($medicos as $m): ?>

                        <?php
                        $fecha = $d->format('Y-m-d');
                        $idMed = $m['id_medico'];
                        $cita  = $citasIndexadas[$fecha][$hora][$idMed] ?? null;
                        ?>

                        <td>

                            <?php if ($cita): ?>
                                <?php $estado = strtolower($cita['estado_cita']); ?>

                                <a href="<?= Enlaces::BASE_URL ?>citas/form_editar?id=<?= $cita['id_cita'] ?>" class="cita-link">
                                    <div class="cita-ocupada color-estado-<?= $estado ?>">
                                        <strong>
                                            <?= htmlspecialchars(
                                                ($cita['nombre_paciente'] ?? 'Hueco libre') . ' ' .
                                                ($cita['apellidos_paciente'] ?? '')
                                            ) ?>
                                        </strong>
                                        <?= ucfirst($estado) ?>
                                    </div>
                                </a>

                            <?php else: ?>

                                <form action="<?= Enlaces::BASE_URL ?>citas/form_crear" method="POST">
                                    <input type="hidden" name="fecha" value="<?= $fecha ?>">
                                    <input type="hidden" name="hora" value="<?= $hora ?>">
                                    <input type="hidden" name="id_medico" value="<?= $idMed ?>">
                                    <button class="btn-hueco">Libre</button>
                                </form>

                            <?php endif; ?>

                        </td>

                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>

</table>
</div>

</body>
</html>