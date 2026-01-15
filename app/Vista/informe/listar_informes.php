<?php use Mediagend\App\Config\Enlaces; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informes del Paciente</title>
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/formInforme.css">
</head>

<body>

<h2>📂 Informes del paciente</h2>

<?php if (empty($informes)): ?>
    <p>No hay informes registrados.</p>
<?php else: ?>

<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Ver informe</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($informes as $informe): ?>
            <tr>
                <td><?= htmlspecialchars($informe['fecha_generacion_informe']) ?></td>
                <td>
                    <a href="<?= Enlaces::BASE_URL ?>informe/ver?id=<?= $informe['id_informe'] ?>" target="_blank">
                        📄 Abrir PDF
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

</body>
</html>