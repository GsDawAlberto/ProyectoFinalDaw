<?php

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Cita;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* SOLO PACIENTE */
if (!isset($_SESSION['paciente'])) {
    exit('Acceso denegado');
}

$pdo = BaseDatos::getConexion();
$citaModel = new Cita();

/* Datos del paciente desde sesión */
$idPaciente        = $_SESSION['paciente']['id_paciente'];
$nombrePaciente    = $_SESSION['paciente']['nombre_paciente'];
$apellidosPaciente = $_SESSION['paciente']['apellidos_paciente'];

/* Citas del paciente */
$citas = $citaModel->mostrarPorPaciente($pdo, $idPaciente);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis citas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::STYLES_URL ?>citas_paciente.css">
</head>

<body>

    <h2>📋 Mis citas</h2>
    <p class="paciente-nombre">
        <?= htmlspecialchars($nombrePaciente . ' ' . $apellidosPaciente) ?>
    </p>
    <p>🟡 Pendiente · 🔵 Confirmada · 🟢 Realizada · 🔴 Cancelada</p>

    <div class="citas-container">

        <?php if (empty($citas)): ?>
            <p class="sin-citas">No tienes citas programadas.</p>
        <?php endif; ?>

        <?php foreach ($citas as $cita):

            $estado = strtolower($cita['estado_cita']);
        ?>
            
            <div class="cita-card color-estado-<?= $estado ?>">

                <div class="cita-col">
                    <p><strong>📅 Fecha:</strong>
                        <?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?>
                    </p>
                    <p><strong>⏰ Hora:</strong>
                        <?= substr($cita['hora_cita'], 0, 5) ?>
                    </p>
                    <p><strong>👨‍⚕️ Médico:</strong><br>
                        <?= htmlspecialchars(
                            $cita['nombre_medico'] . ' ' . $cita['apellidos_medico']
                        ) ?>
                    </p>
                    <p class="estado"><strong>Estado de la cita: </strong>
                        <?= ucfirst($estado) ?>
                    </p>
                </div>

                <div class="cita-col">
                    <p><strong>📝 Motivo:</strong><br>
                        <?= htmlspecialchars($cita['motivo_cita'] ?: 'No especificado') ?>
                    </p>

                </div>

            </div>
        <?php endforeach; ?>

    </div>

</body>

</html>