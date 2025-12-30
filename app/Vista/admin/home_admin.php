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
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos propios -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/homeAdmin.css">

    <title>Document</title>
</head>

<body lang="es">

    <div class="layout">

        <!-- Sidebar -->
        <aside class="sidebar">
            <h1>PANEL DE ADMINISTRADOR</h1>
            <h2>Bienvenido, <?= htmlspecialchars($nombreUsuario) ?></h2>
            <div class="layout_header"><?php include_once Enlaces::LAYOUT_PATH . 'header.php'; ?></div>
            
            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>admin/home/clinicas')"><i class="fa-solid fa-truck-medical"></i>Clínicas</button>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>clinica/loguear_clinica')"><i class="fa-solid fa-file-import"></i>Insertar</button>
            
            <!-- <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>admin/home/configuracion')"><i class="fa-solid fa-gears"></i>Configuración</button> -->
            <a href="<?= Enlaces::BASE_URL ?>admin/login_admin"><i class="fa-solid fa-arrow-right-from-bracket"></i>Cerrar sesión</a>
        </aside>

        <!-- Contenedor derecho -->
        <main class="contenido">
            <iframe id="visor" src="<?= Enlaces::BASE_URL ?>admin/home/clinicas" frameborder="0" style="width: 100%; height: 100%;"></iframe>
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