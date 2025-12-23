<?php
namespace Mediagend\App\Vista\clinica\contenido_clinica_home;

use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Paciente;
use Mediagend\App\Config\Enlaces;

$pdo = BaseDatos::getConexion();
$usuarioModel = new Paciente();

$id_usuario = $_GET['id_usuario'] ?? null;
$id_usuario = is_numeric($id_usuario) ? (int)$id_usuario : null;

$resultado = $usuarioModel->mostrarPaciente($pdo, $id_usuario);
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
    <h1> HOME DE USUARIOS.PHP</h1>
    <form method="GET">
        <label>Buscar usuario por ID:</label>
        <input type="number" name="id_usuario" placeholder="ID de usuario">
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
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Telefono</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>

                <?php if (isset($resultado['id_usuario'])): ?>
                    <!-- UN usuario -->
                    <tr>
                        <td><?= htmlspecialchars($resultado['id_usuario']) ?></td>
                        <td><?= htmlspecialchars($resultado['nombre']) ?></td>
                        <td><?= htmlspecialchars($resultado['apellidos']) ?></td>
                        <td><?= htmlspecialchars($resultado['dni']) ?></td>
                        <td><?= htmlspecialchars($resultado['telefono']) ?></td>
                        <td><?= htmlspecialchars($resultado['email']) ?></td>
                    </tr>

                <?php else: ?>
                    <!-- VARIOS usuarios -->
                    <?php foreach ($resultado as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                            <td><?= htmlspecialchars($usuario['apellidos']) ?></td>
                            <td><?= htmlspecialchars($usuario['dni']) ?></td>
                            <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

            </tbody>
        </table>
        </div>

    <?php endif; ?>


</html>