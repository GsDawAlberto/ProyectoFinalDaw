<?php

use Mediagend\App\Config\Enlaces;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/homeAdmin.css">

    <title>Panel Administrador</title>
</head>

<body lang="es">

    <!-- PANEL COMPLETO (ESCRITORIO) -->
    <div class="layout">

        <aside class="sidebar">
            <h1>PANEL DE ADMINISTRADOR</h1>
            <h2 class="welcome-msg">Bienvenido, <?= htmlspecialchars($nombreUsuario) ?></h2>
            <div class="layout_header"><?php include_once Enlaces::LAYOUT_PATH . 'header.php'; ?></div>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>admin/home/clinicas')">
                <i class="fa-solid fa-truck-medical"></i> Mostrar Clínicas
            </button>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>clinica/loguear_clinica')">
                <i class="fa-solid fa-file-import"></i> Insertar Clínica
            </button>

            <!-- Logout -->
            <a href="<?= Enlaces::BASE_URL ?>admin/logout" class="menu-btn logout-btn">
                <i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i> Cerrar sesión
            </a>
        </aside>

        <main class="contenido">
            <iframe id="visor" src="<?= Enlaces::BASE_URL ?>admin/home/clinicas" frameborder="0"></iframe>
        </main>

    </div>

    <!-- VISTA SOLO MÓVIL -->
    <div class="mobile-view">
        <p>Este panel de administrador no está disponible en dispositivos móviles</p>

        <a href="<?= Enlaces::BASE_URL ?>admin/logout" class="menu-btn logout-btn">
            <i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i> Cerrar sesión
        </a>
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
