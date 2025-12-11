<?php
namespace Mediagend\App\Config;

class Enlaces
{
    // URL base del proyecto
    public const BASE_URL = '/mediagend/public/';
    // URL para los archivos de estilos
    public const STYLES_URL = self::BASE_URL . 'styles/';
    // Ruta absoluta en el sistema de archivos (no URL)
    public const BASE_PATH = __DIR__ . "/../../";

    // Ruta a las vistas
    public const VIEW_PATH = self::BASE_PATH . "app/vista/";

    // Ruta layouts
    public const LAYOUT_PATH = self::VIEW_PATH . "layout/";

    // Ruta a los controladores (si la necesitas)
    public const CONTROLLER_PATH = self::BASE_PATH . "app/controlador/";
}
