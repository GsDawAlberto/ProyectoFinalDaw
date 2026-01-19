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

    /********************** URLS DE LOGOS ***********************/
    public const LOGOS_URL = '/mediagend/app/imagenes_registros/imagenes_clinicas/';
    // Ruta absoluta en el sistema de archivos (no URL)

    public const IMG_PACIENTE_URL = '/mediagend/app/imagenes_registros/imagenes_pacientes/';
    // Ruta absoluta en el sistema de archivos (no URL)

    public const IMG_MEDICO_URL = '/mediagend/app/imagenes_registros/imagenes_medicos/';
    // Ruta absoluta en el sistema de archivos (no URL)

    public const PDF_INFORMES_URL = '/mediagend/app/imagenes_registros/informes_clinicos/';

    /******************************** PATHS *********************************/
    public const BASE_PATH = __DIR__ . "/../../";

    // Ruta a las vistas
    public const VIEW_PATH = self::BASE_PATH . "app/vista/";

    // Ruta a contenidos de las vistas
    public const VIEW_CONTENT_ADMIN_PATH = self::VIEW_PATH . "admin/contenido_admin_home/";

     public const VIEW_CONTENT_CLINICA_PATH = self::VIEW_PATH . "clinica/contenido_clinica_home/";

    public const VIEW_CONTENT_MEDICO_PATH = self::VIEW_PATH . "medico/contenido_medico_home/";

    public const VIEW_CONTENT_PACIENTE_PATH = self::VIEW_PATH . "paciente/contenido_paciente_home/";
    // Ruta layouts
    public const LAYOUT_PATH = self::VIEW_PATH . "layout/";

    // Ruta a los controladores (si se necesita)
    public const CONTROLLER_PATH = self::BASE_PATH . "app/controlador/";
}
