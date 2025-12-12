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
    <!-- <?php include_once Enlaces::LAYOUT_PATH . 'header.php'; ?>
    <h1>Panel de Administrador</h1>
    <p>Bienvenido, <?= htmlspecialchars($nombreUsuario) ?>!</p>

    <a href="<?= Enlaces::BASE_URL ?>admin/login">Cerrar sesión</a>

    <?php include_once Enlaces::LAYOUT_PATH . 'footer.php'; ?> -->

    <div class="layout">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>Clínica</h2>

        <button class="menu-btn" onclick="cargar(<?php include Enlaces::BASE_URL . 'admin/home/citas';?>)">🔹 Listado de pacientes</button>
        <button class="menu-btn" onclick="cargar('paginas/insertar.php')">➕ Insertar paciente</button>
        <button class="menu-btn" onclick="cargar('paginas/citas.php')">📅 Citas</button>
        <button class="menu-btn" onclick="cargar('paginas/configuracion.php')">⚙️ Configuración</button>
    </aside>

    <!-- Contenedor derecho -->
    <main class="contenido">
        <iframe id="visor" src="<?php include Enlaces::BASE_URL . 'admin/home/citas';?>"></iframe>
    </main>

</div>

<script>
    function cargar(url) {
    document.getElementById("visor").src = url;
}
</script>

</body>

</html>