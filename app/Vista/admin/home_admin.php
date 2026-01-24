<?php

use Mediagend\App\Config\Enlaces;

if (!isset($_SESSION['admin'])) {
    header("Location: " . Enlaces::BASE_URL . "admin/login");
    exit;
}

$nombreUsuario = $_SESSION['admin']['nombre_admin'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Language" content="es">
    <meta name="google" content="notranslate">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Solo_logo.png">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos propios -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/homeAdmin.css">

    <title>Document</title>
</head>

<body lang="es">

    <div class="layout">

        <aside class="sidebar">
    <h1>PANEL DE ADMINISTRADOR</h1>
    <h2 class="welcome-msg">Bienvenido, <?= htmlspecialchars($nombreUsuario) ?></h2>
    <div class="layout_header"><?php include_once Enlaces::LAYOUT_PATH . 'header.php'; ?></div>

    <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>admin/home/clinicas')">
        <i class="fa-solid fa-truck-medical"></i>Mostrar Clínicas
    </button>

    <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>clinica/loguear_clinica')">
        <i class="fa-solid fa-file-import"></i>Insertar Clinica
    </button>

    <!-- Logout -->
    <a href="<?= Enlaces::BASE_URL ?>admin/logout" class="menu-btn logout-btn">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión
    </a>

    <!-- Mensaje para móviles -->
    <div class="mobile-warning">Este contenido no se puede mostrar en dispositivos móviles</div>
</aside>

<main class="contenido">
    <iframe id="visor" src="<?= Enlaces::BASE_URL ?>admin/home/clinicas" frameborder="0"></iframe>
</main>

    </div>

    <footer>
        <?php include_once Enlaces::LAYOUT_PATH . 'footer.php'; ?>
    </footer>

    <script>
        function cargar(url) {
            document.getElementById("visor").src = url;
        }
    </script>

</body>

</html>