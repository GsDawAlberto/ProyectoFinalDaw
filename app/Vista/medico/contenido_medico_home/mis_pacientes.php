<?php

namespace Mediagend\App\Vista\medico\contenido_medico_home;

use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Paciente;
use Mediagend\App\Config\Enlaces;

session_start();
$medicoSesion = $_SESSION['medico']['id_medico'];
$clinicaSesion = $_SESSION['clinica']['id_clinica'];

/***************************  PACIENTES  *********************************/
$pdo = BaseDatos::getConexion();
$pacienteModel = new Paciente();

$busqueda = $_GET['buscar'] ?? null;
$resultado = $pacienteModel->mostrarPaciente($pdo, $busqueda);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::STYLES_URL ?>tablas.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
    <title>Pacientes</title>
</head>

<body>
    <h1> HOME DE USUARIOS.PHP</h1>
    <form method="GET">
        <label for="buscar">Buscar un paciente</label>
        <input type="text" name="buscar"
            placeholder="Buscar por nombre, DNI, email..."
            value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
        <button type="submit">Buscar</button>
    </form>

    <?php if ($resultado === 'ERR_USUARIO_03'): ?>
        <p>Error al obtener mis pacientes</p>

    <?php elseif (empty($resultado)): ?>
        <p>No se encontraron pacientes</p>

    <?php else: ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>DNI</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <th>Citas</th>
                        <th>Ver informes</th>
                        <th>Crear informe</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($resultado as $paciente): ?>

                        <!-- <?php if ((int)$clinicaSesion === (int)$paciente['id_clinica'] && (int)$medicoSesion === (int)$paciente['id_medico']): ?> -->
                        <tr>
                            <td>
                                <div>
                                    <img class="foto_paciente" src="<?= Enlaces::IMG_PACIENTE_URL . $paciente['foto_paciente'] ?>"
                                        alt="Foto paciente"
                                        width="60" height="60">
                                </div>
                            </td>
                            <td><?= htmlspecialchars($paciente['usuario_paciente']) ?></td>
                            <td><?= htmlspecialchars($paciente['nombre_paciente']) ?></td>
                            <td><?= htmlspecialchars($paciente['apellidos_paciente']) ?></td>
                            <td><?= htmlspecialchars($paciente['dni_paciente']) ?></td>
                            <td><?= htmlspecialchars($paciente['telefono_paciente']) ?></td>
                            <td><?= htmlspecialchars($paciente['email_paciente']) ?></td>

                            <td>
                                <form action="<?= Enlaces::BASE_URL ?>citas/pacientes" method="GET"
                                    onsubmit="return confirm('¿Deseas ver las citas del paciente: <?= $paciente['nombre_paciente'] . ' ' . $paciente['apellidos_paciente'] ?>');">
                                    <input type="hidden" name="id_paciente" value="<?= $paciente['id_paciente'] ?>">
                                    <input type="hidden" name="nombre_paciente" value="<?= $paciente['nombre_paciente'] ?>">
                                    <input type="hidden" name="apellidos_paciente" value="<?= $paciente['apellidos_paciente'] ?>">
                                    <button type="submit">📅 Citas</button>
                                </form>
                            </td>

                            <td>
                                <form action="<?= Enlaces::BASE_URL ?>informe/listar" method="POST"
                                    onsubmit="return confirm('¿Deseas ver los informes de: <?=$paciente['nombre_paciente'] . ' ' . $paciente['apellidos_paciente'] ?>');">
                                    <input type="hidden" name="id_paciente" value="<?= $paciente['id_paciente'] ?>">
                                    <button type="submit">📂 Ver informes</button>
                                </form>
                            </td>

                            <td>
                                <form action="<?= Enlaces::BASE_URL . 'informe/crear' ?>" method="POST"
                                    onsubmit="return confirm('¿Deseas crear un INFORME para: <?= $paciente['nombre_paciente'] . ' ' . $paciente['apellidos_paciente'] ?>');">
                                    <input type="hidden" name="id_paciente" value="<?= $paciente['id_paciente'] ?>">
                                    <button type="submit">📄 Crear informe</button>
                                </form>
                            </td>

                        </tr>
                    <?php endif; ?>

                    <!-- <?php endforeach; ?> -->

                </tbody>
            </table>
        </div>

    <?php endif; ?>

</body>

</html>