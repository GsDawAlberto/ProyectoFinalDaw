<?php
/**
 * Archivo de configuración de enlaces y rutas
 *
 * Contiene constantes globales relacionadas con URLs públicas
 * y rutas internas del sistema de archivos usadas en la aplicación.
 *
 * @package Mediagend\App\Config
 */

namespace Mediagend\App\Config;

/**
 * Clase Enlaces
 *
 * Define las URLs y paths base utilizados en la aplicación,
 * incluyendo rutas a recursos estáticos, vistas, controladores
 * e imágenes.
 */
class Enlaces
{
    /**
     * URL base del proyecto
     *
     * @var string
     */
    public const BASE_URL = '/mediagend/public/';

    /**
     * URL base para los archivos de estilos CSS
     *
     * @var string
     */
    public const STYLES_URL = self::BASE_URL . 'styles/';

    /**
     * URL para los logos de clínicas
     *
     * @var string
     */
    public const LOGOS_URL = '/mediagend/app/imagenes_registros/imagenes_clinicas/';

    /**
     * URL para las imágenes de pacientes
     *
     * @var string
     */
    public const IMG_PACIENTE_URL = '/mediagend/app/imagenes_registros/imagenes_pacientes/';

    /**
     * URL para las imágenes de médicos
     *
     * @var string
     */
    public const IMG_MEDICO_URL = '/mediagend/app/imagenes_registros/imagenes_medicos/';

    /**
     * URL para iconos e imágenes públicas
     *
     * @var string
     */
    public const IMG_ICONO_URL = '/mediagend/public/img/';

    /**
     * URL para los informes clínicos en PDF
     *
     * @var string
     */
    public const PDF_INFORMES_URL = '/mediagend/app/imagenes_registros/informes_clinicos/';

    /**
     * URL base para los archivos JavaScript
     *
     * @var string
     */
    public const JS_URL = '/mediagend/app/scripts_javaScript/';

    /**
     * Ruta base absoluta del proyecto en el sistema de archivos
     *
     * @var string
     */
    public const BASE_PATH = __DIR__ . "/../../";

    /**
     * Ruta base a las vistas
     *
     * @var string
     */
    public const VIEW_PATH = self::BASE_PATH . "app/vista/";

    /**
     * Ruta a los contenidos de vistas del administrador
     *
     * @var string
     */
    public const VIEW_CONTENT_ADMIN_PATH = self::VIEW_PATH . "admin/contenido_admin_home/";

    /**
     * Ruta a los contenidos de vistas de clínicas
     *
     * @var string
     */
    public const VIEW_CONTENT_CLINICA_PATH = self::VIEW_PATH . "clinica/contenido_clinica_home/";

    /**
     * Ruta a los contenidos de vistas de médicos
     *
     * @var string
     */
    public const VIEW_CONTENT_MEDICO_PATH = self::VIEW_PATH . "medico/contenido_medico_home/";

    /**
     * Ruta a los contenidos de vistas de pacientes
     *
     * @var string
     */
    public const VIEW_CONTENT_PACIENTE_PATH = self::VIEW_PATH . "paciente/contenido_paciente_home/";

    /**
     * Ruta a los layouts de las vistas
     *
     * @var string
     */
    public const LAYOUT_PATH = self::VIEW_PATH . "layout/";

    /**
     * Ruta a los controladores de la aplicación
     *
     * @var string
     */
    public const CONTROLLER_PATH = self::BASE_PATH . "app/controlador/";
}
