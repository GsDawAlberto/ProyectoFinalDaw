<?php 
use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Informe;

// Comprobar que se recibe el ID del paciente
if (!isset($_POST['id_paciente'])) {
    header("Location: " . Enlaces::BASE_URL . "medico/contenido_medico_home/mis_pacientes.php");
    exit;
}

$id_paciente = filter_input(INPUT_POST, 'id_paciente', FILTER_VALIDATE_INT);
$pdo = BaseDatos::getConexion();
$informeModel = new Informe();

// Obtener todos los informes del paciente
$informesBD = $informeModel->listarPorPaciente($pdo, $id_paciente);

// Ruta de la carpeta de PDFs
$rutaCarpeta = Enlaces::BASE_PATH . 'app/imagenes_registros/informes_clinicos/';

// Filtrar solo los informes cuyo archivo existe
$informes = array_filter($informesBD, function($inf) use ($rutaCarpeta) {
    return !empty($inf['archivo_pdf_informe']) && is_file($rutaCarpeta . $inf['archivo_pdf_informe']);
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informes del Paciente</title>
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/listadoInformes.css">
</head>
<body>

<div class="container">
    <h2>📂 Informes del Paciente</h2>

    <?php if (empty($informes)): ?>
        <div class="no-informes">
            ⚠️ No hay informes disponibles para este paciente.
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
                        <td><?= htmlspecialchars($informe['fecha_generacion_informe']) ?></td>
                        <td>
                            <a class="pdf-link" href="<?= Enlaces::BASE_URL ?>informe/ver?id=<?= $informe['id_informe'] ?>" target="_blank">
                                📄 Abrir PDF
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