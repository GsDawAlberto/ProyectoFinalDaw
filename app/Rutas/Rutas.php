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

            // ---------- VISTAS ADMIN HOME ----------

            case 'admin/home/citas':
                $controller = new AdminController();
                $controller->home_citas();
                break;

            case 'admin/home/clinicas':
                $controller = new AdminController();
                $controller->home_clinicas();
                break;

            case 'admin/home/configuracion':
                $controller = new AdminController();
                $controller->home_configuracion();
                break;

            case 'admin/home/insertar':
                $controller = new AdminController();
                $controller->home_insertar();
                break;
                


            // ---------- CLINICA ----------
            case 'clinica/login':
                $controller = new ClinicaController();
                $controller->login();
                break;

            case 'clinica/home':
                $controller = new ClinicaController();
                $controller->home();
                break;

            case 'clinica/registrar':
                $controller = new ClinicaController();
                $controller->registrar();
                break;

            case 'clinica/acceder':
                $controller = new ClinicaController();  
                $controller->acceder();
                break;


            // ---------- USUARIO ----------
            case 'usuario/login':
                $controller = new UsuarioController();
                $controller->login();
                break;
                
            /* case 'usuario/home':
                $controller = new UsuarioController();
                $controller->home();
                break;

            case 'usuario/loguear':
                $controller = new UsuarioController();
                $controller->loguear();
                break;

            case 'usuario/registrar':
                $controller = new UsuarioController();
                $controller->registrar();
                break;

            case 'usuario/acceder':
                $controller = new UsuarioController();
                $controller->acceder();
                break; */


            // ---------- ERROR ----------
            default:
                http_response_code(404);
                echo "Página no encontrada: $url";
                break;
        }
    }
}
