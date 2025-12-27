<?php

namespace Mediagend\App\Vista\admin\contenido_admin_home;

use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Administrador;
use Mediagend\App\Modelo\Clinica;


session_start();
$administrador = $_SESSION['admin']['id_admin'];

$pdo = BaseDatos::getConexion();

$clinicaModel = new Clinica();

$id_clinica = $_GET['id_clinica'] ?? null;
$id_clinica = is_numeric($id_clinica) ? (int)$id_clinica : null;

$resultado = $clinicaModel->mostrarClinica($pdo, $id_clinica);


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Language" content="es">
    <meta name="google" content="notranslate">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::STYLES_URL ?>tablas.css">
    <title>Document</title>
</head>

<body lang="es">
    <h1> HOME DE CLINICAS.PHP</h1>
    
    <form method="GET">
        <label>Buscar clínica por Administrador:</label>

        <select name="id_admin">
            <option value="">Todos</option>
            <?php foreach ($resultado as $clinica):?>
                <option value="<?= $clinica['id_admin'] ?>"><?= $clinica['id_admin'] ?></option>
            <?php endforeach; ?>
        </select>
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
                        <th>Admin</th>
                        <th>Clinica</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Modificar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if ($id_admin !== null): ?>
                        <?php foreach ($resultado as $clinica): ?>
                            <?= $l = (int)$clinica['id_admin'] ?>
                            <?php if ( $l === $id_admin): ?>
                                <!-- UNA clínica -->
                                <tr>
                                    <td><?= $clinica['id_admin'] ?></td>
                                    <td><?= $clinica['usuario_clinica'] ?></td>
                                    <td><?= $clinica['nombre_clinica'] ?></td>
                                    <td><?= $clinica['direccion_clinica'] ?></td>
                                    <td><?= $clinica['email_clinica'] ?></td>
                                    <td><?= $clinica['telefono_clinica'] ?></td>
                                    <?php if ((int)$_SESSION['admin']['id_admin'] === (int)$clinica['id_admin']): ?>

                                    <!-- MODIFICAR -->
                                    <td>
                                        <form action="<?= Enlaces::BASE_URL ?>admin/clinica/editar" method="GET">
                                            <input type="hidden" name="id_clinica" value="<?= $clinica['id_clinica'] ?>">
                                            <button type="submit" class="btn-submit">✏️ Modificar</button>
                                        </form>
                                    </td>

                                    <!-- ELIMINAR -->
                                    <td>
                                        <form action="<?= Enlaces::BASE_URL ?>admin/clinica/eliminar" method="POST"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar esta clínica?');">
                                            <input type="hidden" name="id_clinica" value="<?= $clinica['id_clinica'] ?>">
                                            <button type="submit" class="btn-delete">🗑️ Eliminar</button>
                                        </form>
                                    </td>

                                <?php else: ?>

                                    <!-- Celdas vacías si no es del admin -->
                                    <td>-<?= $id_admin ?></td>
                                    <td>-</td>

                                <?php endif; ?>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <!-- VARIAS clínicas -->
                        <?php foreach ($resultado as $clinica): ?>
                            <tr>
                                <td><?= $clinica['id_admin'] ?></td>
                                <td><?= $clinica['usuario_clinica'] ?></td>
                                <td><?= $clinica['nombre_clinica'] ?></td>
                                <td><?= $clinica['direccion_clinica'] ?></td>
                                <td><?= $clinica['email_clinica'] ?></td>
                                <td><?= $clinica['telefono_clinica'] ?></td>
                                <?php if ((int)$_SESSION['admin']['id_admin'] === (int)$clinica['id_admin']): ?>

                                    <!-- MODIFICAR -->
                                    <td>
                                        <form action="<?= Enlaces::BASE_URL ?>admin/clinica/editar" method="GET">
                                            <input type="hidden" name="id_clinica" value="<?= $clinica['id_clinica'] ?>">
                                            <button type="submit" class="btn-submit">✏️ Modificar</button>
                                        </form>
                                    </td>

                                    <!-- ELIMINAR -->
                                    <td>
                                        <form action="<?= Enlaces::BASE_URL ?>admin/clinica/eliminar" method="POST"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar esta clínica?');">
                                            <input type="hidden" name="id_clinica" value="<?= $clinica['id_clinica'] ?>">
                                            <button type="submit" class="btn-delete">🗑️ Eliminar</button>
                                        </form>
                                    </td>

                                <?php else: ?>

                                    <!-- Celdas vacías si no es del admin -->
                                    <td><?=  $id_admin?></td>
                                    <td>-</td>

                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>
            </table>

        <?php endif; ?>
</body>

</html>