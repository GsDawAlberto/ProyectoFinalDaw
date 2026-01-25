<?php

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Cita;
use Mediagend\App\Modelo\Clinica;
use Mediagend\App\Modelo\Medico;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* SOLO PACIENTE */
if (!isset($_SESSION['paciente'])) {
    exit('Acceso denegado');
}

/* DATOS DEL PACIENTE */
$paciente = $_SESSION['paciente'];

/* FOTO DEL PACIENTE */
$fotoPaciente = isset($paciente['foto_paciente']) && $paciente['foto_paciente'] !== ''
    ? $paciente['foto_paciente']
    : 'imagen_paciente_por_defecto.jpg';

$fotoURL = Enlaces::IMG_PACIENTE_URL . $fotoPaciente;



$pdo = BaseDatos::getConexion();

$citaModel   = new Cita();
$medicoModel = new Medico();
$clinicaModel = new Clinica();





/* ===========================
   CLÍNICA
=========================== */
$nombreClinica = null;
if (!empty($paciente['id_clinica'])) {
    $clinica = $clinicaModel->mostrarClinicaPorId($pdo, $paciente['id_clinica']);
    if ($clinica) {
        $nombreClinica = $clinica['nombre_clinica'];
    }
}

/* ===========================
   MÉDICO ASIGNADO (SI EXISTE)
=========================== */
$nombreMedico = null;
if (!empty($paciente['id_medico'])) {
    $medico = $medicoModel->mostrarMedicoPorId($pdo, $paciente['id_medico']);
    if ($medico) {
        $nombreMedico = $medico['nombre_medico'] . ' ' . $medico['apellidos_medico'];
    }
}

/* ===========================
   PRÓXIMA CITA
=========================== */
$citas = $citaModel->mostrarPorPaciente($pdo, $paciente['id_paciente']);
$hoy = date('Y-m-d');
$proximaCita = null;

foreach ($citas as $cita) {
    if ($cita['fecha_cita'] >= $hoy) {
        $proximaCita = $cita;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio paciente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::STYLES_URL ?>inicioPaciente.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
</head>

<body>

<div class="header">
    <!-- FOTO PACIENTE -->
    <img class="foto-paciente" src="<?= htmlspecialchars($fotoURL) ?>" alt="Foto del paciente">
    <h2>Bienvenido/a, <?= htmlspecialchars($paciente['nombre_paciente']) ?></h2>
</div>

<div class="contenedor">

    <!-- CLÍNICA -->
    <div class="card">
        <h3>Su clínica</h3>
        <p><strong></strong><?= htmlspecialchars($nombreClinica) ?></strong></p>
    </div>

    <!-- MÉDICO -->
    <div class="card">
        <h3>Médico asignado</h3>
            <p><strong><?= htmlspecialchars($nombreMedico ?? 'No tienes un médico asignado') ?></strong></p>
    </div>

    <!-- PRÓXIMA CITA -->
    <div class="card">
        <h3>Próxima cita</h3>
        <?php if ($proximaCita): ?>
            <p>
                <strong>Fecha:</strong> <?= date('d/m/Y', strtotime($proximaCita['fecha_cita'])) ?><br>
                <strong>Hora:</strong> <?= substr($proximaCita['hora_cita'], 0, 5) ?><br>
                <strong>Estado:</strong> <?= ucfirst($proximaCita['estado_cita']) ?>
            </p>
        <?php else: ?>
            <p class="muted"><strong>No tiene citas pendientes</strong></p>
        <?php endif; ?>
    </div>

    <!-- NOTIFICACIONES (INFORMATIVO) -->
    <div class="card">
        <h3>🔔 Notificaciones</h3>
        <ul>
            <li>📩 No tiene mensajes nuevos</li>
            <li>⏰ Recordatorios activos</li>
            <li>📄 Informes disponibles</li>
        </ul>
        <small class="muted">* Información orientativa *</small>
    </div>

</div>

</body>
</html>
