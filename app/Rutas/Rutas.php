<?php

namespace Mediagend\App\Rutas;

use Mediagend\App\Controlador\LobbyController;
use Mediagend\App\Controlador\AdminController;
use Mediagend\App\Controlador\ClinicaController;
use Mediagend\App\Controlador\UsuarioController;

class Rutas
{

    public function getRuta()
    {

        $url = $_GET['url'] ?? 'lobby/index';

        switch ($url) {

            // ---------- LOBBY ----------
            case 'lobby/index':
                $controller = new LobbyController();
                $controller->index();
                break;


            // ---------- ADMINISTRADOR ----------
            case 'admin/login':
                $controller = new AdminController();
                $controller->login();
                break;

             case 'admin/home':
                $controller = new AdminController();
                $controller->home();
                break;

            case 'admin/loguear':
                $controller = new AdminController();
                $controller->loguear();
                break;

            case 'admin/registrar':
                $controller = new AdminController();   
                $controller->registrar();
                break;
            
            case 'admin/acceder':
                $controller = new AdminController();   
                $controller->acceder();
                break;

            


            // ---------- CLINICA ----------
            case 'clinica/login':
                $controller = new ClinicaController();
                $controller->login();
                break;

            /*  case 'empresa/home':
                $controller = new EmpresaController();
                $controller->home();
                break; */


            // ---------- USUARIO ----------
            case 'usuario/login':
                $controller = new UsuarioController();
                $controller->login();
                break;/* 

            case 'usuario/home':
                $controller = new UsuarioController();
                $controller->home();
                break; */


            // ---------- ERROR ----------
            default:
                http_response_code(404);
                echo "Página no encontrada: $url";
                break;
        }
    }
}
