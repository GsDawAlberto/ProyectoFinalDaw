<?php

use Mediagend\App\Config\Enlaces;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Clínica</title>

    <!-- Estilos reutilizados -->
    <link rel="stylesheet" href="<?= Enlaces::BASE_URL ?>styles/formInforme.css">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
</head>

<body>

    <div class="container informe-form">

        <header>
            <h2>Crear informe</h2>
        </header>
        <form action="<?= Enlaces::BASE_URL ?>informe/guardar" method="POST" class="form">
            <input type="hidden" name="id_paciente" value="<?= $_POST['id_paciente'] ?>">
            <input type="hidden" name="id_medico" value="<?= $_SESSION['medico']['id_medico'] ?>">
            <input type="hidden" name="id_clinica" value="<?= $_SESSION['clinica']['id_clinica'] ?>">

            <div class="form-group informe-textarea">
                <label>Diagnóstico</label>
                <textarea name="diagnostico" required></textarea>
            </div>

            <div class="form-group informe-textarea">
                <label>Tratamiento</label>
                <textarea name="tratamiento" required></textarea>
            </div>

            <button type="submit" class="btn-submit">
                Generar Informe
            </button>
        </form>


    </div>

</body>

</html>