<?php
use Mediagend\App\Config\Enlaces;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Paciente</title>

    <!-- Estilos propios -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">

</head>
<body>

<div class="container">

    <header>
        <h1>Paciente</h1>
        <p>Acceso al panel de control</p>
    </header>

    <form action="<?= Enlaces::BASE_URL ?>paciente/acceder" method="POST" class="form">

        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario_paciente" required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password_paciente" required>
        </div>

        <button type="submit" class="btn-submit">Ingresar</button>

    </form>

    <footer>
        <?php include_once Enlaces::LAYOUT_PATH . 'footer.php'; ?>
    </footer>
    
</body>
</html>