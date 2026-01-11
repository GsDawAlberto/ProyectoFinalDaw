<?php
use Mediagend\App\Config\Enlaces;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lobby Mediagend</title>

    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos propios -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/lobby.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container">
    
    <header>
        <h1>Bienvenido a</h1>
        <?php include_once Enlaces::LAYOUT_PATH . 'header.php';?>
        <p>Gestiona tu clínica y pacientes de forma segura y rápida</p>
    </header>

    <nav class="lobby-nav">
        <a href="<?= Enlaces::BASE_URL ?>admin/login_admin" class="lobby-btn btn-admin">
            <i class="fas fa-user-shield"></i>
            Administrador
        </a>

        <a href="<?= Enlaces::BASE_URL ?>clinica/login_clinica" class="lobby-btn btn-clinica">
            <i class="fas fa-hospital"></i>
            Clínicas
        </a>

        <a href="<?= Enlaces::BASE_URL ?>paciente/login_paciente" class="lobby-btn btn-usuario">
            <i class="fas fa-user"></i>
            Pacientes
        </a>
    </nav>

    <footer>
        <?php include_once Enlaces::LAYOUT_PATH . 'footer.php'; ?>
    </footer>
    
</div>

</body>
</html>
