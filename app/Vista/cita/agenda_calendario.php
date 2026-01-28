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
$idClinica = $_SESSION['clinica']['id_clinica'];

/* MÉDICOS */
$medicos = $medicoModel->listarPorClinica($pdo, $idClinica);

/* CITAS */
$mostrarCita = $citaModel->mostrarPorClinica($pdo, $idClinica);

/* HORAS */
$horas = [
    '09:00','10:00','11:00','12:00','13:00',
    '15:00','16:00','17:00','18:00','19:00','20:00'
];

/* SEMANA (20 DÍAS DESDE HOY) */
$semana = [];
$hoy = new DateTime('today');

for ($i = 0; $i < 20; $i++) {
    $semana[] = (clone $hoy)->modify("+$i day");
}

/* DÍAS EN ESPAÑOL */
$dias = [
    'Monday'    => 'Lunes',
    'Tuesday'   => 'Martes',
    'Wednesday' => 'Miércoles',
    'Thursday'  => 'Jueves',
    'Friday'    => 'Viernes',
    'Saturday'  => 'Sábado',
    'Sunday'    => 'Domingo'
];

/* INDEXAR CITAS */
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::STYLES_URL ?>agenda_calendario.css">
    <link rel="icon" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
</head>

<body>

<h2>📅 Agenda de la clínica</h2>
<p>🟡 Pendiente · 🔵 Confirmada · 🟢 Realizada · 🔴 Cancelada</p>

<?php if (empty($medicos)): ?>
    <p class="sin_medicos">
        Para agendar, la clínica tiene que tener algún médico insertado.
    </p>
<?php else: ?>

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
                                                ($cita['nombre_paciente'] ?? 'Hueco') . ' ' .
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

<?php endif; ?>

</body>
</html>
