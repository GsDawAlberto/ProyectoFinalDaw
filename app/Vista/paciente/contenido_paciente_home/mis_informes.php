<?php

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Informe;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* SOLO PACIENTE */
if (!isset($_SESSION['paciente'])) {
    exit('Acceso denegado');
}

$pdo = BaseDatos::getConexion();
$informeModel = new Informe();

/* Datos del paciente desde sesión */
$idPaciente        = $_SESSION['paciente']['id_paciente'];
$nombrePaciente    = $_SESSION['paciente']['nombre_paciente'];
$apellidosPaciente = $_SESSION['paciente']['apellidos_paciente'];

/* Obtener informes del paciente */
$informesBD = $informeModel->listarPorPaciente($pdo, $idPaciente);

/* Ruta de PDFs */
$rutaCarpeta = Enlaces::BASE_PATH . 'app/imagenes_registros/informes_clinicos/';

/* Filtrar solo PDFs existentes */
$informes = array_filter($informesBD, function ($inf) use ($rutaCarpeta) {
    return !empty($inf['archivo_pdf_informe']) &&
           is_file($rutaCarpeta . $inf['archivo_pdf_informe']);
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis informes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/listadoInformes.css">
</head>

<body>

<div class="container">

    <h2>📂 Mis informes</h2>
    <p class="paciente-nombre">
        <?= htmlspecialchars($nombrePaciente . ' ' . $apellidosPaciente) ?>
    </p>

    <?php if (empty($informes)): ?>
        <div class="no-informes">
            ⚠️ No tienes informes disponibles.
        </div>
    <?php else: ?>

        <table class="tabla-informes">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Informe</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($informes as $informe): ?>
                    <tr>
                        <td>
                            <?= date('d/m/Y', strtotime($informe['fecha_generacion_informe'])) ?>
                        </td>
                        <td>
                            <a class="pdf-link"
                               href="<?= Enlaces::BASE_URL ?>informe/ver?id=<?= $informe['id_informe'] ?>"
                               target="_blank">
                                📄 Ver informe
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

</div>

</body>
</html>