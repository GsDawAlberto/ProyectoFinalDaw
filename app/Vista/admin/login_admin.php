<?php
use Mediagend\App\Config\Enlaces;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador</title>

    <!-- Estilos propios -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">

</head>
<body>

<div class="container">

    <header>
        <h1>Administrador</h1>
        <p>Acceso al panel de control</p>
    </header>

    <form action="<?= Enlaces::BASE_URL ?>admin/acceder" method="POST" class="form">

        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario_admin" required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password_admin" required>
        </div>

        <button type="submit" class="btn-submit">Ingresar</button>

    </form>

    <div class="extra-links">
        <a href="<?= Enlaces::BASE_URL ?>admin/loguear_admin">Crear cuenta</a>
    </div>

    <footer>
        <?php include_once Enlaces::LAYOUT_PATH . 'footer.php'; ?>
    </footer>

</div>

</body>
</html>
