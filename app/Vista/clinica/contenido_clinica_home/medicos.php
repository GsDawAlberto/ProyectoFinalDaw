<?php

namespace Mediagend\App\Vista\clinica\contenido_clinica_home;

use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Medico;
use Mediagend\App\Config\Enlaces;

session_start();
$clinicaSesion = $_SESSION['clinica']['id_clinica'];

/***************************  PACIENTES  *********************************/
$pdo = BaseDatos::getConexion();
$medicoModel = new Medico();

$busqueda = $_GET['buscar'] ?? null;
$resultado = $medicoModel->mostrarMedico($pdo, $busqueda);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::STYLES_URL ?>tablas.css">
    <title>Pacientes</title>
</head>

<body>
    <h1> HOME DE MEDICOS.PHP</h1>
    <form method="GET">
        <label for="buscar">Buscar un medico</label>
        <input type="text" name="buscar"
            placeholder="Buscar por nombre, DNI, email..."
            value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
        <button type="submit">Buscar</button>
    </form>

    <?php if ($resultado === 'ERR_MEDICO_03'): ?>
        <p>Error al obtener médicos</p>

    <?php elseif (empty($resultado)): ?>
        <p>No se encontraron médicos con esa busqueda</p>

    <?php else: ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Colegiado</th>
                        <th>Especialidad</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <th>Modificar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($resultado as $medico): ?>

                        <?php if ((int)$clinicaSesion === (int)$medico['id_clinica']): ?>
                            <tr>
                                <td>
                                    <div>
                                        <img class="foto_medico" src="<?= Enlaces::IMG_MEDICO_URL . $medico['foto_medico'] ?>"
                                            alt="Foto Médico"
                                            width="60" height="60">
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($medico['numero_colegiado']) ?></td>
                                <td><?= htmlspecialchars($medico['especialidad_medico']) ?></td>
                                <td><?= htmlspecialchars($medico['nombre_medico']) ?></td>
                                <td><?= htmlspecialchars($medico['apellidos_medico']) ?></td>
                                <td><?= htmlspecialchars($medico['telefono_medico']) ?></td>
                                <td><?= htmlspecialchars($medico['email_medico']) ?></td>

                                <td>
                                    <form action="<?= Enlaces::BASE_URL ?>medico/modificar" method="GET">
                                        <input type="hidden" name="id_medico" value="<?= $medico['id_medico'] ?>">
                                        <button type="submit">✏️ Modificar</button>
                                    </form>
                                </td>

                                <td>
                                    <form action="<?= Enlaces::BASE_URL ?>medico/eliminar" method="POST"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar este médico: <?= $medico['nombre_medico'] . ' ' . $medico['apellidos_medico'] ?> ?');">
                                        <input type="hidden" name="id_medico" value="<?= $medico['id_medico'] ?>">
                                        <button type="submit">🗑️ Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    <?php endif; ?>

</body>

</html>