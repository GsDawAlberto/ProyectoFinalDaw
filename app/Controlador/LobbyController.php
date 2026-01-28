<?php
namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;

/**
 * Controlador del Lobby
 *
 * Gestiona las vistas públicas principales del sistema,
 * incluyendo la página de inicio y la página de error 404.
 *
 * @package Mediagend\App\Controlador
 */
class LobbyController {

    /**
     * Página principal del lobby
     *
     * Muestra la vista de inicio del sistema.
     *
     * @return void
     */
    public function index() {
        /* require "../app/Vista/lobby/index.php"; */
        require Enlaces::VIEW_PATH . "lobby/index.php";
    }

    /**
     * Página de error 404
     *
     * Muestra una página personalizada cuando se accede
     * a una ruta inexistente.
     *
     * @return void
     */
    public function error404() {
        ////// Se verá el error 404 y texto y una imagen //////

        /* http_response_code(404);
        echo "ERROR 404 - Página no encontrada"; */
        require Enlaces::VIEW_PATH . "lobby/error404.php";
    }
}
