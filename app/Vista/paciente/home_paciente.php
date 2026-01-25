<?php

use Mediagend\App\Config\Enlaces;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['paciente'])) {
    exit('Acceso denegado');
}

$nombrePaciente = $_SESSION['paciente']['nombre_paciente'] . ' ' . $_SESSION['paciente']['apellidos_paciente'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Área Paciente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/homePaciente.css">
</head>

<body>
    <header>
        <h3><?= $nombrePaciente ?></h3>
    </header>
    
    <div class="layout">

        <!-- CONTENIDO -->
        <main class="contenido">
            <iframe
                class="contenedor_iframe"
                id="visor"
                src="<?= Enlaces::BASE_URL ?>paciente/home/inicio"
                frameborder="0">
            </iframe>
        </main>

    </div>

    
     <!--   *******************        BARRA INFERIOR      *********************      -->
    <nav class="bottom-bar">

        <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>paciente/home/inicio')">
            <i class="fa-solid fa-house"></i>
            <span>Inicio</span>
        </button>

        <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>paciente/home/citas')">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>Cita</span>
        </button>

        <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>paciente/home/informes')">
            <i class="fa-solid fa-file-medical"></i>
            <span>Informes</span>
        </button>

        <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>paciente/home/ajustes')">
            <i class="fa-solid fa-sliders"></i>
            <span>Ajustes</span>
        </button>

        <a class="menu-btn" href="<?= Enlaces::BASE_URL ?>paciente/logout"><i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i>SALIR</a>
        

    </nav>

    <footer>
        <?php include_once Enlaces::LAYOUT_PATH . 'footer.php'; ?>
    </footer>

    <script>
        function cargar(url) {
            document.getElementById('visor').src = url;
        }
    </script>

</body>

</html>