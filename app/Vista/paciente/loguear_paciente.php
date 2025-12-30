<?php

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Administrador;
use Mediagend\App\Modelo\Clinica;

$pdo = BaseDatos::getConexion();

/************************** ADMINISTRADORES ******************************/
$clinicaModel = new Clinica();
$clinic = $clinicaModel->mostrarClinica($pdo, null);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Paciente</title>

    <!-- Estilos reutilizados -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
</head>

<body>

    <div class="container">

        <header>
            <h2>Registrar Nueva Clínica</h2>
        </header>

        <form action="<?= Enlaces::BASE_URL ?>paciente/registrar_paciente" method="POST" enctype="multipart/form-data" class="form">

            <div class="form-group">
                <label>Nombre del Paciente</label>
                <input type="text" name="nombre_paciente" required>
            </div>

            <div class="form-group">
                <label>Apellidos Paciente</label>
                <input type="text" name="apellidos_paciente" required>
            </div>

            <div class="form-group">
                <label>DNI Paciente</label>
                <input type="text" name="dni_paciente" required>
            </div>

            <div class="form-group">
                <label>Foto del Paciente</label>
                <input type="file" name="foto_paciente" accept="image/*">
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono_paciente" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email_paciente" required>
            </div>

            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="usuario_paciente" required>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password_paciente" required>
            </div>

            <div class="form-group">
                <label>Repetir contraseña</label>
                <input type="password" name="password2_paciente" required>
            </div>

            <button type="submit" class="btn-submit">Registrar Clínica</button>
        </form>


    </div>

</body>

</html>