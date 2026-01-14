<?php

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Medico;

/* session_start(); */
$clinicaSesion = $_SESSION['clinica']['id_clinica'];

/************************** CONEXIÓN Y MEDICOS ******************************/
$pdo = BaseDatos::getConexion();
$medicoModel = new Medico();

// Para mantener la selección si viene de un GET (id_medico)
$id_medico = filter_input(INPUT_GET, 'id_medico', FILTER_VALIDATE_INT) ?: null;

// Obtener todos los médicos para el select
$medicos = $medicoModel->mostrarMedico($pdo, null); // null para traer todos
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Estilos reutilizados -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">
    <title>Editar Paciente</title>
</head>

<body>

<div class="container">

        <header>
            <h2>Modificar Paciente</h2>
        </header>

    <form action="<?= Enlaces::BASE_URL ?>paciente/modificar"
        method="POST"
        enctype="multipart/form-data"
        class="form">

        <input type="hidden" name="id_paciente" value="<?= $paciente['id_paciente'] ?>">

        <div class="form-group">
            <label>Nombre del Paciente</label>
            <input type="text" name="nombre_paciente"
                value="<?= htmlspecialchars($paciente['nombre_paciente']) ?>" required>
        </div>

        <div class="form-group">
            <label>Apellidos del Paciente</label>
            <input type="text" name="apellidos_paciente"
                value="<?= htmlspecialchars($paciente['apellidos_paciente']) ?>" required>
        </div>

        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="dni_paciente"
                value="<?= htmlspecialchars($paciente['dni_paciente']) ?>" required>
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono_paciente"
                value="<?= htmlspecialchars($paciente['telefono_paciente']) ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email_paciente"
                value="<?= htmlspecialchars($paciente['email_paciente']) ?>" required>
        </div>

        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario_paciente"
                value="<?= htmlspecialchars($paciente['usuario_paciente']) ?>" required>
        </div>

        <div class="form-group">
                <label>Asignar un Médico (opcional):</label>
                <select name="id_medico">
                    <option value="">Ninguno</option>
                    <?php foreach ($medicos as $medico): ?>
                        <?php if ($clinicaSesion === (int)$medico['id_clinica']): ?>
                            <option value="<?= $medico['id_medico'] ?>"
                                <?= ($id_medico === (int)$medico['id_medico']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($medico['nombre_medico']) ?> <?= htmlspecialchars($medico['apellidos_medico']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

        <div class="form-group">
            <label>Foto del Paciente</label>
            <input type="file" name="foto_paciente" accept="image/*">

            <?php if (!empty($paciente['foto_paciente'])): ?>
                <small>Foto actual:</small><br>
                <img src="<?= Enlaces::IMG_PACIENTE_URL . $paciente['foto_paciente'] ?>"
                    alt="Foto actual"
                    width="80" height="80">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">
            Guardar cambios
        </button>

    </form>
    </div>
</body>

</html>