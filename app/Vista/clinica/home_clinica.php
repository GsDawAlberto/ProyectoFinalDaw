<?php

use Mediagend\App\Config\Enlaces;

if (!isset($_SESSION['clinica'])) {
    header("Location: " . Enlaces::BASE_URL . "clinica/login");
    exit;
}

$nombreUsuario = $_SESSION['clinica']['nombre_clinica'];
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
            <div class="layout_header">
                <img class="logo_clinica" src="<?= Enlaces::LOGOS_URL . $_SESSION['clinica']['foto_clinica'] ?>"
                    alt="Foto clínica"
                    width="120">
            </div>
            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>clinica/home/pacientes')"><i class="fa-regular fa-user"></i>Mostrar Pacientes</button>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>clinica/home/medicos')"><i class="fa-solid fa-user-doctor"></i>Mostrar Médicos</button>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>paciente/loguear_paciente')"><i class="fa-solid fa-file-import"></i>Insertar Paciente</button>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>medico/loguear_medico')"><i class="fa-solid fa-file-import"></i>Insertar Médico</button>

            <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>citas/ver_agenda')"><i class="fa-solid fa-calendar-check"></i>Ver Agenda</button>

            <a class="menu-btn" href="<?= Enlaces::BASE_URL ?>medico/login_medico">
                <i class="fa-solid fa-user-doctor"></i> Acceso Médico
            </a>

            <!-- <button class="menu-btn" onclick="cargar('<?= Enlaces::BASE_URL ?>clinica/home/configuracion')"><i class="fa-solid fa-gears"></i>Configuración</button> -->
            <a href="<?= Enlaces::BASE_URL ?>clinica/login_clinica"><i class="fa-solid fa-arrow-right-from-bracket"></i>Cerrar sesión</a>
        </aside>

        <!-- Contenedor derecho -->
        <main class="contenido">
            <iframe id="visor" src="<?= Enlaces::BASE_URL ?>clinica/home/pacientes" frameborder="0" style="width: 100%; height: 100%;"></iframe>
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