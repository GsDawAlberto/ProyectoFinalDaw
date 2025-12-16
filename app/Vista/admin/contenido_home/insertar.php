<?php
use Mediagend\App\Config\Enlaces;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Clínica</title>

    <!-- Estilos reutilizados -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
</head>
<body>

<div class="container">

    <header>
        <h2>Registrar Nueva Clínica</h2>
    </header>

    <form action="<?= Enlaces::BASE_URL ?>clinica/registrar" method="POST" class="form">

        <div class="form-group">
            <label>Nombre de la clínica</label>
            <input type="text" name="nombre" required>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" required>
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario" required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Repetir contraseña</label>
            <input type="password" name="password2" required>
        </div>

        <button type="submit" class="btn-submit">Registrar Clínica</button>
    </form>

    <div class="extra-links">
        <a href="<?= Enlaces::BASE_URL ?>admin/home/clinicas">Volver al panel</a>
    </div>

</div>

</body>
</html>
