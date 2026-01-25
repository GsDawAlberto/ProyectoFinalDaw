<?php

use Mediagend\App\Config\Enlaces;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['clinica'])) {
    header("Location: " . Enlaces::BASE_URL . "clinica/login");
    exit;
}

if (!isset($_SESSION['medico'])) {
    header("Location: " . Enlaces::BASE_URL . "medico/login");
    exit;
}

$nombreClinica = $_SESSION['clinica']['nombre_clinica'];
$nombreMedico = $_SESSION['medico']['nombre_medico'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/homeMedico.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">

    <title>Panel Médico</title>
</head>

<body>

    <!-- PANEL COMPLETO (SOLO ESCRITORIO) -->
    <div class="layout">

        <aside class="sidebar">
            <h1>PANEL MÉDICO</h1>
            <h2>Bienvenid@, <?= $_SESSION['medico']['nombre_medico'] . ' ' . $_SESSION['medico']['apellidos_medico'] ?></h2>

            <div class="layout_header">
                <img class="logo_clinica"
                     src="<?= Enlaces::IMG_MEDICO_URL . $_SESSION['medico']['foto_medico'] ?>"
                     alt="Foto médico"
                     width="120">
            </div>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>medico/home/pacientes')">
                <i class="fa-regular fa-user"></i> Mis Pacientes
            </button>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>citas/ver_agenda_medico')">
                <i class="fa-solid fa-calendar-check"></i> Mi Agenda
            </button>

            <!-- Logout -->
            <a href="<?= Enlaces::BASE_URL ?>medico/logout" class="menu-btn logout-btn">
                <i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i> Cerrar sesión
            </a>
        </aside>

        <main class="contenido">
            <iframe id="visor"
                    src="<?= Enlaces::BASE_URL ?>citas/ver_agenda_medico"
                    frameborder="0">
            </iframe>
        </main>

    </div>

    <!-- VISTA SOLO MÓVIL -->
    <div class="mobile-view">
        <p>Este panel de médico no está disponible en dispositivos móviles</p>

        <a href="<?= Enlaces::BASE_URL ?>medico/logout" class="menu-btn logout-btn">
            <i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i>
            Cerrar sesión
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
