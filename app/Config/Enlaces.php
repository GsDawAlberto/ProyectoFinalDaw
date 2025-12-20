<?php
namespace Mediagend\App\Config;

class Enlaces
{
    /***********************   URL   **************************/
    // URL base del proyecto
    public const BASE_URL = '/mediagend/public/';
    // URL para los archivos de estilos

    /********************** URLS DE ESTILOS ***********************/
    public const STYLES_URL = self::BASE_URL . 'styles/';
    // Ruta absoluta en el sistema de archivos (no URL)

    /******************************** PATHS *********************************/
    public const BASE_PATH = __DIR__ . "/../../";

    // Ruta a las vistas
    public const VIEW_PATH = self::BASE_PATH . "app/vista/";

    // Ruta a contenidos de las vistas
    public const VIEW_CONTENT_ADMIN_PATH = self::VIEW_PATH . "admin/contenido_admin_home/";

    // Ruta layouts
    public const LAYOUT_PATH = self::VIEW_PATH . "layout/";

    // Ruta a los controladores (si la necesitas)
    public const CONTROLLER_PATH = self::BASE_PATH . "app/controlador/";
}
