<?php

use Mediagend\App\Config\Enlaces;

if (!isset($_SESSION['admin'])) {
    header("Location: " . Enlaces::BASE_URL . "admin/login");
    exit;
}

$nombreUsuario = $_SESSION['admin']['nombre'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include_once Enlaces::LAYOUT_PATH . 'header.php'; ?>
    <h1>Panel de Administrador</h1>
    <p>Bienvenido, <?= htmlspecialchars($nombreUsuario) ?>!</p>

    <a href="<?= Enlaces::BASE_URL ?>admin/login">Cerrar sesión</a>

    <?php include_once Enlaces::LAYOUT_PATH . 'footer.php'; ?>
</body>

</html>