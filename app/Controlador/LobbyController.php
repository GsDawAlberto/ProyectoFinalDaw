<?php
namespace Mediagend\App\Controlador;
use Mediagend\App\Config\Enlaces;

class LobbyController {
    public function index() {
        /* require "../app/Vista/lobby/index.php"; */
        require Enlaces::VIEW_PATH . "lobby/index.php";
    }
}