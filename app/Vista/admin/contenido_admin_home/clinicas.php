<?php

namespace Mediagend\App\Vista\admin\contenido_admin_home;

use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Clinica;
use Mediagend\App\Config\Enlaces;

$pdo = BaseDatos::getConexion();
$clinicaModel = new Clinica();

$id_clinica = $_GET['id_clinica'] ?? null;
$id_clinica = is_numeric($id_clinica) ? (int)$id_clinica : null;

$resultado = $clinicaModel->mostrarClinica($pdo, $id_clinica);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::STYLES_URL ?>tablas.css">
    <title>Document</title>
</head>

<body>
    <h1> HOME DE CLINICAS.PHP</h1>
    <form method="GET">
        <label>Buscar clínica por ID:</label>
        <input type="number" name="id_clinica" placeholder="ID de clínica">
        <button type="submit">Buscar</button>
    </form>

    <?php if ($resultado === 'ERR_CLINICA_03'): ?>
        <p>Error al obtener clínicas</p>

    <?php elseif (empty($resultado)): ?>
        <p>No se encontraron clínicas</p>

    <?php else: ?>

        <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Admin</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>

                <?php if (isset($resultado['id_clinica'])): ?>
                    <!-- UNA clínica -->
                    <tr>
                        <td><?= $resultado['id_clinica'] ?></td>
                        <td><?= $resultado['id_admin'] ?></td>
                        <td><?= $resultado['nombre_clinica'] ?></td>
                        <td><?= $resultado['direccion_clinica'] ?></td>
                        <td><?= $resultado['email_clinica'] ?></td>
                        <td><?= $resultado['telefono_clinica'] ?></td>
                    </tr>

                <?php else: ?>
                    <!-- VARIAS clínicas -->
                    <?php foreach ($resultado as $clinica): ?>
                        <tr>
                            <td><?= $clinica['id_clinica'] ?></td>
                            <td><?= $clinica['id_admin'] ?></td>
                            <td><?= $clinica['nombre_clinica'] ?></td>
                            <td><?= $clinica['direccion_clinica'] ?></td>
                            <td><?= $clinica['email_clinica'] ?></td>
                            <td><?= $clinica['telefono_clinica'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

            </tbody>
        </table>

    <?php endif; ?>
</body>

</html>