<?php

namespace Mediagend\App\Vista\clinica\contenido_clinica_home;

use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Paciente;
use Mediagend\App\Config\Enlaces;

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
        <p>Error al obtener usuarios</p>

    <?php elseif (empty($resultado)): ?>
        <p>No se encontraron usuarios</p>

    <?php else: ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>DNI</th>
                        <th>Telefono</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($resultado as $paciente): ?>

                        <?php
                        // Filtrado por administrador
                        if ($busqueda !== null) {
                            continue;
                        }
                        ?>

                        <tr>
                            <td><?= htmlspecialchars($paciente['usuario_paciente']) ?></td>
                            <td><?= htmlspecialchars($paciente['nombre_paciente']) ?></td>
                            <td><?= htmlspecialchars($paciente['apellidos_paciente']) ?></td>
                            <td><?= htmlspecialchars($paciente['dni_paciente']) ?></td>
                            <td><?= htmlspecialchars($paciente['telefono_paciente']) ?></td>
                            <td><?= htmlspecialchars($paciente['email_paciente']) ?></td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    <?php endif; ?>

</body>

</html>