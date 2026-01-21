<?php

namespace Mediagend\App\Controlador;

class Helper
{
    public static function mostrar_error(string $mensaje, string $url_volver = null): void
    {
        $volverHtml = $url_volver ? "<a href='$url_volver'>Volver</a>" : "";
        echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Error</title>
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f4f6f8;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .error-box {
        background: #fff;
        border: 2px solid #e74c3c;
        padding: 30px 40px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        max-width: 400px;
    }
    .error-box h1 {
        margin: 0 0 15px 0;
        color: #e74c3c;
        font-size: 24px;
    }
    .error-box p {
        margin: 10px 0;
        font-size: 16px;
        color: #333;
    }
    .error-box a {
        display: inline-block;
        margin-top: 15px;
        padding: 8px 16px;
        background: #3498db;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
    }
    .error-box a:hover {
        background: #2980b9;
    }
</style>
</head>
<body>
    <div class="error-box">
        <h1>⚠️ Error ⚠️</h1>
        <p>$mensaje</p>
        $volverHtml
    </div>
</body>
</html>
HTML;
        exit;
    }
}
