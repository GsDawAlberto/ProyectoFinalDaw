<?php
use Mediagend\App\Config\Enlaces;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="180x180" href="<?= Enlaces::IMG_ICONO_URL ?>Icono.png">
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        img {
            margin-top: 10%;
            max-width: 100%;
            height: auto;
        }
    </style>
    <title>Document</title>
</head>
<body>
    <img src="<?= Enlaces::BASE_URL ?>img/Error 404_2.png" alt="Imagen de error 404">
    <h1>Error 404 - Página no encontrada</h1>
    <p>Lo sentimos, pero <strong>Mediagend</strong> no puede encontrar la página solicitada.</p>
</body>
</html>