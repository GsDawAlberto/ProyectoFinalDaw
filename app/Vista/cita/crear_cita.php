<?php
use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Medico;
use Mediagend\App\Modelo\Paciente;


if (!isset($_SESSION['clinica'])) {
    exit('Acceso denegado');
}

$pdo = BaseDatos::getConexion();

$medicoModel   = new Medico();
$pacienteModel = new Paciente();

/* LISTAS */
$medicos = $medicoModel->listarPorClinica($pdo, $_SESSION['clinica']['id_clinica']);
$pacientes = $pacienteModel->listarPorClinica($pdo, $_SESSION['clinica']['id_clinica']);

$fecha = $_POST['fecha'] ?? '';
$hora  = $_POST['hora'] ?? '';
$idMedico = (int)$_POST['id_medico'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/crearCita.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
    <title>Crear Cita</title>
</head>

<body>
    <form class="form-cita" method="POST" action="<?= Enlaces::BASE_URL ?>citas/crear_hueco">

    <h2>🗓 Crear cita</h2>

    <input type="hidden" name="fecha" value="<?= $fecha ?>">
    <input type="hidden" name="hora" value="<?= $hora ?>">
    <input type="hidden" name="id_medico" value="<?= $idMedico ?>">


    <label>Paciente (opcional)</label>
    <select name="id_paciente">
        <option value="">Hueco libre</option>
        <?php foreach ($pacientes as $p): ?>
            <option value="<?= $p['id_paciente'] ?>">
                <?= $p['nombre_paciente'] ?> <?= $p['apellidos_paciente'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Guardar cita</button>
</form>
</body>

</html>