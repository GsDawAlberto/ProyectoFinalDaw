<?php

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Cita;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['paciente']) && !isset($_SESSION['medico'])) {
    exit('Acceso denegado');
}

$pdo = BaseDatos::getConexion();
$citaModel = new Cita();

/* CASO 1: paciente logueado */
if (isset($_SESSION['paciente'])) {
    $idPaciente = $_SESSION['paciente']['id_paciente'];
}

/* CASO 2: médico viendo paciente */ elseif (isset($_SESSION['medico']) && isset($_GET['id_paciente'])) {
    $idPaciente = (int) $_GET['id_paciente'];
} else {
    exit('Paciente no especificado');
}

/* Nombre del paciente para el título */
$nombrePaciente = $_GET['nombre_paciente'];
$apellidosPaciente = $_GET['apellidos_paciente'];


/* Citas del paciente */
$citas = $citaModel->mostrarPorPaciente($pdo, $idPaciente);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis citas</title>
    <link rel="stylesheet" href="<?= Enlaces::STYLES_URL ?>citas_paciente.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
</head>

<body>

    <h2>📋 Mis citas "<?= htmlspecialchars($nombrePaciente . ' ' . $apellidosPaciente) ?>"</h2>
    <div class="citas-container">

        <?php if (empty($citas)): ?>
            <p>No tienes citas programadas.</p>
        <?php endif; ?>

        <?php foreach ($citas as $cita):
            $estado = strtolower($cita['estado_cita']);
        ?>
            <div class="cita-card color-estado-<?= $estado ?>">
                <div>
                    <p><strong>📅 Fecha:</strong> <?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?></p>
                    <p><strong>⏰ Hora:</strong> <?= substr($cita['hora_cita'], 0, 5) ?></p>
                    <p><strong>👨‍⚕️ Médico:</strong>
                        <?= htmlspecialchars($cita['nombre_medico'] . ' ' . $cita['apellidos_medico']) ?>
                    </p>
                </div>
                <div>
                    <p><strong>📝 Motivo:</strong>
                        <?= htmlspecialchars($cita['motivo_cita'] ?? 'No especificado') ?>
                    </p>
                    <p><strong>Estado:</strong> <?= ucfirst($estado) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>

</html>