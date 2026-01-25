<?php
use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Cita;

if (!isset($_SESSION['clinica']) || !isset($_SESSION['medico'])) {
    exit('Acceso denegado');
}

$pdo = BaseDatos::getConexion();
$citaModel = new Cita();

$idMedicoActual = $_SESSION['medico']['id_medico'];

// Solo las citas del médico logueado
$mostrarCita = $citaModel->mostrarPorMedico($pdo, $idMedicoActual);

/* HORAS */
$horas = ['08:00','09:00','10:00','11:00','12:00','13:00','15:00','16:00','17:00','18:00','19:00','20:00'];

/* SEMANA (L–V) */
$semana = [];
$dia = new DateTime('monday this week');
for ($i = 0; $i < 5; $i++) {
    $semana[] = (clone $dia)->modify("+$i day");
}

/* FORMATEO DE LOS DÍAS EN ESPAÑOL */
$dias = ['Monday'=>'Lunes','Tuesday'=>'Martes','Wednesday'=>'Miércoles','Thursday'=>'Jueves','Friday'=>'Viernes'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agenda Clínica</title>
    <link rel="stylesheet" href="<?= Enlaces::STYLES_URL ?>agenda_calendario.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
</head>

<body>

    <h2>📅 Mi agenda semanal "<?= $_SESSION['medico']['nombre_medico'].' '.$_SESSION['medico']['apellidos_medico'] ?>"</h2>
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

                <?php
                $estado = strtolower(trim($citaEncontrada['estado_cita']));
                $esMiCita = ($citaEncontrada['id_medico'] == $idMedicoActual);
                ?>

                <?php if ($esMiCita): ?>
                    <!-- Su propia cita: editable -->
                    <a href="<?= Enlaces::BASE_URL ?>citas/form_editar?id=<?= $citaEncontrada['id_cita'] ?>" class="cita-link">
                        <div class="cita-ocupada color-estado-<?= $estado ?>">
                            <strong>
                                Medico: <?= htmlspecialchars($citaEncontrada['nombre_medico'] . ' ' . $citaEncontrada['apellidos_medico']) ?>
                            </strong>
                            Paciente: <?= htmlspecialchars(
                                            ($citaEncontrada['nombre_paciente'] ?? 'Sin Paciente') . ' ' . ($citaEncontrada['apellidos_paciente'] ?? '')
                                        ) ?><br>
                            Estado: <?= ucfirst($estado) ?>
                        </div>
                    </a>
                <?php else: ?>
                    <!-- Cita de otro médico: no editable -->
                    <div class="cita-ocupada color-estado-ocupada">
                        Ocupada
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- Hueco libre -->
                <div class="cita-libre">
                    Libre
                </div>
            <?php endif; ?>
        </td>
    <?php endforeach; ?>
</tr>
<?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>