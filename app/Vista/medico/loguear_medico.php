<?php

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Clinica;

/* USAR ESTA PARTE ??????
$pdo = BaseDatos::getConexion();

************************** CLINICAS ******************************
$clinicaModel = new Clinica();
$admins = $clinicaModel->mostrarClinica($pdo, $id_clinica); */
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Médico</title>

    <!-- Estilos reutilizados -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
</head>

<body>

    <div class="container">

        <header>
            <h2>Registrar Nuevo Médico</h2>
        </header>

        <form action="<?= Enlaces::BASE_URL ?>medico/registrar_medico" method="POST" enctype="multipart/form-data" class="form">

            <div class="form-group">
                <label>Foto de la medico</label>
                <input type="file" name="foto_medico" accept="image/*">
            </div>

            <div class="form-group">
                <label>Número de Colegiado</label>
                <input type="text" name="numero_colegiado" required>
            </div>

            <div class="form-group">
                <label>Especialidad medico </label>
                <input type="text" name="especialidad_medico" required>
            </div>

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre_medico" required>
            </div>

            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" name="apellidos_medico" required>
            </div>

            <div class="form-group">
                <label>telefono</label>
                <input type="text" name="telefono_medico" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email_medico" required>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password_medico" required>
            </div>

            <div class="form-group">
                <label>Repetir contraseña</label>
                <input type="password" name="password2_medico" required>
            </div>

            <button type="submit" class="btn-submit">Registrar Médico</button>
        </form>


    </div>

</body>

</html>