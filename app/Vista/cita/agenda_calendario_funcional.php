<?php

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Cita;

if (!isset($_SESSION['clinica'])) {
    exit('Acceso denegado');
}

$pdo = BaseDatos::getConexion();
$citaModel = new Cita();
$mostrarCita = $citaModel->mostrarPorClinica($pdo, $_SESSION['clinica']['id_clinica']);

/* HORAS */
$horas = ['09:00', '10:00', '11:00', '12:00', '13:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];

/* SEMANA (L–V) */
$semana = [];
$dia = new DateTime('monday this week');
for ($i = 0; $i < 5; $i++) {
    $semana[] = (clone $dia)->modify("+$i day");
}

/* FORMATEO DE LOS DÍAS EN ESPAÑOL */
$dias = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agenda Clínica</title>
    <link rel="stylesheet" href="<?= Enlaces::STYLES_URL ?>agenda_calendario.css">
</head>

<body>

    <h2>📅 Agenda semanal</h2>
    <h2>🟡 Pendiente 🔵 Confirmada 🟢 Realizada 🔴 No asiste</h2>

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
                        $citaEncontrada = null;

                        // Buscamos cita para esta fecha y hora
                        foreach ($mostrarCita as $c) {
                            if ($c['fecha_cita'] === $fecha && substr($c['hora_cita'], 0, 5) === $hora) {
                                $citaEncontrada = $c;
                                break;
                            }
                        }
                    ?>
                        <td>
                            <?php if ($citaEncontrada): ?>
                                <!-- Este script se utilizará estado de la cita y cambiar el color según el estado de la cita con css -->
                                <?php $estado = strtolower(trim($citaEncontrada['estado_cita'])); // confirmada, realizada, cancelada, pendiente 
                                ?>
                                <!-- Todo el contenedor se convierte en un enlace al formulario de modificación del registro de la cita -->
                                <a href="<?= Enlaces::BASE_URL ?>citas/form_editar?id=<?= $citaEncontrada['id_cita'] ?>" class="cita-link">
                                    <div class="cita-ocupada color-estado-<?= $estado ?>">
                                        <strong>
                                            Medico: <?= htmlspecialchars($citaEncontrada['nombre_medico'] . ' ' . $citaEncontrada['apellidos_medico']) ?>
                                        </strong>
                                        Paciente: <?= htmlspecialchars(
                                                        ($citaEncontrada['nombre_paciente']) . ' ' . ($citaEncontrada['apellidos_paciente'] ?? 'Sin Paciente Asignado')
                                                    ) ?><br>
                                        Estado: <?= ucfirst($estado) ?> <!-- Aquí se aplica el color según el estado -->
                                    </div>
                                </a>

                            <?php else: ?>

                                <form action="<?= Enlaces::BASE_URL ?>citas/form_crear" method="POST">
                                    <input type="hidden" name="fecha" value="<?= $fecha ?>">
                                    <input type="hidden" name="hora" value="<?= $hora ?>">
                                    <button class="btn-hueco">libre</button>
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