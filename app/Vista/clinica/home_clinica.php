<?php

use Mediagend\App\Config\Enlaces;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['clinica'])) {
    header("Location: " . Enlaces::BASE_URL . "clinica/login");
    exit;
}

$nombreUsuario = $_SESSION['clinica']['nombre_clinica'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/homeClinica.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">

    <title>Panel Clínica</title>
</head>

<body>

    <!-- PANEL COMPLETO (SOLO ESCRITORIO) -->
    <div class="layout">

        <aside class="sidebar">
            <h1>PANEL DE CLÍNICA</h1>
            <h2>Bienvenido, <?= htmlspecialchars($nombreUsuario) ?></h2>

            <div class="layout_header">
                <img class="logo_clinica"
                     src="<?= Enlaces::LOGOS_URL . $_SESSION['clinica']['foto_clinica'] ?>"
                     alt="Foto clínica"
                     width="120">
            </div>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>clinica/home/pacientes')">
                <i class="fa-regular fa-user"></i> Mostrar Pacientes
            </button>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>clinica/home/medicos')">
                <i class="fa-solid fa-user-doctor"></i> Mostrar Médicos
            </button>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>paciente/loguear_paciente')">
                <i class="fa-solid fa-file-import"></i> Insertar Paciente
            </button>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>medico/loguear_medico')">
                <i class="fa-solid fa-file-import"></i> Insertar Médico
            </button>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>citas/ver_agenda')">
                <i class="fa-solid fa-calendar-check"></i> Ver Agenda
            </button>

            <a class="menu-btn" href="<?= Enlaces::BASE_URL ?>medico/login_medico">
                <i class="fa-solid fa-user-doctor"></i> Acceso Médico
            </a>

            <a href="<?= Enlaces::BASE_URL ?>clinica/logout" class="menu-btn logout-btn">
                <i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i> Cerrar sesión
            </a>
        </aside>

        <main class="contenido">
            <iframe id="visor"
                    src="<?= Enlaces::BASE_URL ?>clinica/home/pacientes"
                    frameborder="0">
            </iframe>
        </main>

    </div>

    <!-- VISTA SOLO MÓVIL -->
    <div class="mobile-view">
        <p>Este panel de clínica no está disponible en dispositivos móviles</p>

        <a href="<?= Enlaces::BASE_URL ?>clinica/logout" class="menu-btn logout-btn">
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
