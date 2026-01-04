<?php

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Administrador;

$pdo = BaseDatos::getConexion();

/************************** ADMINISTRADORES ******************************/
$adminModel = new Administrador();
$admins = $adminModel->mostrarAdmin($pdo, null);
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

        <form action="<?= Enlaces::BASE_URL ?>clinica/registrar_clinica" method="POST" enctype="multipart/form-data" class="form">

            <div class="form-group">
                <label>Foto de la clínica</label>
                <input type="file" name="foto_clinica" accept="image/*">
            </div>

            <div class="form-group">
                <label>Nombre de la clínica</label>
                <input type="text" name="nombre_clinica" required>
            </div>

            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion_clinica" required>
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono_clinica" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email_clinica" required>
            </div>

            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="usuario_clinica" required>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password_clinica" required>
            </div>

            <div class="form-group">
                <label>Repetir contraseña</label>
                <input type="password" name="password2_clinica" required>
            </div>

            <button type="submit" class="btn-submit">Registrar Clínica</button>
        </form>


    </div>

</body>

</html>