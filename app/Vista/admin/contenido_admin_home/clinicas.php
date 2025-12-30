<?php
namespace Mediagend\App\Vista\admin\contenido_admin_home;

use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Administrador;
use Mediagend\App\Modelo\Clinica;

session_start();

$administradorSesion = $_SESSION['admin']['id_admin'];

$pdo = BaseDatos::getConexion();

/************************** CLÍNICAS ******************************/
$clinicaModel = new Clinica();

// MOSTRAR TODAS LAS CLÍNICAS (el filtrado se hace en la vista)
$resultado = $clinicaModel->mostrarClinica($pdo, null);

/************************** ADMINISTRADORES ******************************/
$adminModel = new Administrador();
$id_admin = $_GET['id_admin'] ?? null;
$id_admin = ($id_admin === '' || !is_numeric($id_admin)) ? null : (int)$id_admin;
// 🔹 SIEMPRE todos los admins para el select
$admins = $adminModel->mostrarAdmin($pdo, null);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= Enlaces::STYLES_URL ?>tablas.css">
    <title>Clínicas</title>
</head>

<body>

<h1>HOME DE CLÍNICAS</h1>

<form method="GET">
    <label>Buscar clínica por Administrador:</label>
    <select name="id_admin" onchange="this.form.submit()"> <!-- onchange="this.form.submit(), realiza un submit automático sin un botón -->
        <option value="">Todos</option>

        <?php foreach ($admins as $admin): ?>
            <option value="<?= $admin['id_admin'] ?>"
                <?= ($id_admin === (int)$admin['id_admin']) ? 'selected' : '' ?>>
                <?= $admin['usuario_admin'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- <button type="submit">Buscar</button> -->
</form>

<?php if ($resultado === 'ERR_CLINICA_03'): ?>
    <p>Error al mostrar clínicas</p>

<?php elseif (empty($resultado)): ?>
    <p>No se encontraron clínicas</p>

<?php else: ?>

<div class="table-container">
<table>
    <thead>
        <tr>
            <th>Administrador</th>
            <th>Clínica</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Modificar</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody>

    <?php foreach ($resultado as $clinica): ?>

        <?php
        // Filtrado por administrador
        if ($id_admin !== null && (int)$clinica['id_admin'] !== $id_admin) {
            continue;
        }
        ?>

        <tr>
            <td><?= $clinica['usuario_admin'] ?></td>
            <td><?= $clinica['usuario_clinica'] ?></td>
            <td><?= $clinica['nombre_clinica'] ?></td>
            <td><?= $clinica['direccion_clinica'] ?></td>
            <td><?= $clinica['email_clinica'] ?></td>
            <td><?= $clinica['telefono_clinica'] ?></td>

            <?php if ((int)$administradorSesion === (int)$clinica['id_admin']): ?>

                <td>
                    <form action="<?= Enlaces::BASE_URL ?>clinica/modificar" method="POST">
                        <input type="hidden" name="id_clinica" value="<?= $clinica['id_clinica'] ?>">
                        <button type="submit">✏️ Modificar</button>
                    </form>
                </td>

                <td>
                    <form action="<?= Enlaces::BASE_URL ?>clinica/eliminar" method="POST"
                          onsubmit="return confirm('¿Seguro que deseas eliminar esta clínica?');">
                        <input type="hidden" name="id_clinica" value="<?= $clinica['id_clinica'] ?>">
                        <button type="submit">🗑️ Eliminar</button>
                    </form>
                </td>

            <?php else: ?>
                <td>-</td>
                <td>-</td>
            <?php endif; ?>
        </tr>

    <?php endforeach; ?>

    </tbody>
</table>
</div>

<?php endif; ?>

</body>
</html>
