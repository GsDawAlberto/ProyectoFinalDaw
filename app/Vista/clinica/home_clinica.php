<?php

use Mediagend\App\Config\Enlaces;

if (!isset($_SESSION['clinica'])) {
    header("Location: " . Enlaces::BASE_URL . "clinica/login");
    exit;
}

$nombreUsuario = $_SESSION['clinica']['nombre'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos propios -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/homeClinica.css">

    <title>Document</title>

</head>

<body>

    <div class="layout">

        <!-- Sidebar -->
        <aside class="sidebar">
            <h1>PANEL DE CLÍNICA</h1>
            <h2>Bienvenido, <?= htmlspecialchars($nombreUsuario) ?></h2>
            <div class="layout_header"><?php include_once Enlaces::LAYOUT_PATH . 'header.php'; ?></div>
            
            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>clinica/home/medicos')"><i class="fa-solid fa-user-doctor"></i>Médicos</button>
            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>clinica/home/insertar')"><i class="fa-solid fa-file-import"></i>Insertar</button>
            <!-- <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>clinica/home/configuracion')"><i class="fa-solid fa-gears"></i>Configuración</button> -->
            <a href="<?= Enlaces::BASE_URL ?>clinica/login"><i class="fa-solid fa-arrow-right-from-bracket"></i>Cerrar sesión</a>
        </aside>

        <!-- Contenedor derecho -->
        <main class="contenido">
            <iframe id="visor" src="<?= Enlaces::BASE_URL ?>clinica/home/medicos" frameborder="0" style="width: 100%; height: 100%;"></iframe>
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