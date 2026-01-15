<?php use Mediagend\App\Config\Enlaces; ?>

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
            No hay informes registrados para este paciente.
        </div>
    <?php else: ?>

        <table>
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