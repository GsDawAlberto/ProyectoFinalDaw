<?php
namespace Mediagend\App\Controlador;
use Mediagend\App\Config\Enlaces;

class LobbyController {
    public function index() {
        /* require "../app/Vista/lobby/index.php"; */
        require Enlaces::VIEW_PATH . "lobby/index.php";
    }

    public function error404() {
        /* http_response_code(404);
        echo "ERROR 404 - Página no encontrada"; */
        require Enlaces::VIEW_PATH . "lobby/error404.php";
    }
}