<?php

use Mediagend\App\Config\Enlaces;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Estilos reutilizados -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/form.css">

    <title>Editar Clínica</title>
</head>

<body>
    <div class="container">
    <header>
        <h2>Editar Información de la Clínica</h2>
    </header>

    <form action="<?= Enlaces::BASE_URL ?>clinica/modificar"
        method="POST"
        enctype="multipart/form-data"
        class="form">

        <!-- ID oculto -->
        <input type="hidden" name="id_clinica" value="<?= $clinica['id_clinica'] ?>">

        <div class="form-group">
            <label>Nombre de la Clínica</label>
            <input type="text"
                name="nombre_clinica"
                value="<?= htmlspecialchars($clinica['nombre_clinica']) ?>"
                required>
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text"
                name="telefono_clinica"
                value="<?= htmlspecialchars($clinica['telefono_clinica']) ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email"
                name="email_clinica"
                value="<?= htmlspecialchars($clinica['email_clinica']) ?>">
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text"
                name="direccion_clinica"
                value="<?= htmlspecialchars($clinica['direccion_clinica']) ?>">
        </div>

        <div class="form-group">
            <label>Usuario</label>
            <input type="text"
                name="usuario_clinica"
                value="<?= htmlspecialchars($clinica['usuario_clinica']) ?>"
                required>
        </div>

        <div class="form-group">
            <label>Logo de la Clínica</label>
            <input type="file" name="foto_clinica" accept="image/*">

            <?php if (!empty($clinica['foto_clinica'])): ?>
                <small>Logo actual:</small><br>
                <img src="<?= Enlaces::LOGOS_URL . $clinica['foto_clinica'] ?>"
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