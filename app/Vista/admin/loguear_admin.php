<?php

use Mediagend\App\Config\Enlaces;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Administrador</title>
    <!-- Estilos propios -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
</head>

<body>

    <div class="container">
        <header>
            <h2>Registrar Nuevo Administrador</h2>
        </header>


        <form action="<?= Enlaces::BASE_URL ?>admin/registrar" method="POST" class="form">

            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" name="usuario" required>
            </div>

            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Repetir Contraseña:</label>
                <input type="password" name="password_2" required>
            </div>

            <button type="submit" class="btn-submit">Registrar</button>
        </form>

        <div class="extra-links">
            <a href="<?= Enlaces::BASE_URL ?>admin/login">Volver al login</a>
        </div>

        <footer>
        <?php include_once Enlaces::LAYOUT_PATH . 'footer.php'; ?>
    </footer>
    
    </div>
</body>
</html>