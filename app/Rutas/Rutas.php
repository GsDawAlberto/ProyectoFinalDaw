<?php

namespace Mediagend\App\Rutas;

use Mediagend\App\Controlador\LobbyController;
use Mediagend\App\Controlador\AdminController;
use Mediagend\App\Controlador\ClinicaController;
use Mediagend\App\Controlador\PacienteController;
use Mediagend\App\Controlador\UsuarioController;

class Rutas
{

    public function getRuta()
    {

        $url = $_GET['url'] ?? 'lobby/index';

        switch ($url) {

            // ---------- LOBBY ---------- //
            //***********************************************************************************************//
            case 'lobby/index':
                $controller = new LobbyController();
                $controller->index();
                break;


            // ---------- ADMINISTRADOR ----------//
            //***********************************************************************************************//
            case 'admin/login_admin':
                $controller = new AdminController();
                $controller->login();
                break;

             case 'admin/home_admin':
                $controller = new AdminController();
                $controller->home();
                break;

            case 'admin/loguear_admin':
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

            /* case 'admin/home/citas':
                $controller = new AdminController();
                $controller->home_citas();
                break; */

            case 'admin/home/clinicas':
                $controller = new AdminController();
                $controller->home_clinicas();
                break;

            /* case 'admin/home/configuracion':
                $controller = new AdminController();
                $controller->home_configuracion();
                break; */

            case 'admin/home/insertar':
                $controller = new AdminController();
                $controller->home_insertar();
                break;
            
            // ---------- GUARDAR CONFIGURACIÓN ----------
            /* case 'admin/guardar-config':
                $controller = new AdminController();
                $controller->guardar_configuracion();
                break; */
                


            // ---------- CLINICA ---------- //
            //***********************************************************************************************//
            case 'clinica/login_clinica':
                $controller = new ClinicaController();
                $controller->login();
                break;

            case 'clinica/home_clinica':
                $controller = new ClinicaController();
                $controller->home();
                break;

            case 'clinica/registrar_clinica':
                $controller = new ClinicaController();
                $controller->registrar();
                break;

            case 'clinica/loguear':
                $controller = new ClinicaController();
                $controller->loguear();
                break;

            case 'clinica/acceder':
                $controller = new ClinicaController();  
                $controller->acceder();
                break;


            // ---------- USUARIO ---------- //
            //***********************************************************************************************//
            case 'usuario/login_usuario':
                $controller = new PacienteController();
                $controller->login();
                break;
                
             /* case 'usuario/home_usuario':
                $controller = new UsuarioController();
                $controller->home();
                break;

            case 'usuario/loguear_usuario':
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


            // ---------- ERROR ---------- //
            //***********************************************************************************************//
            default:
                http_response_code(404);
                echo "Página no encontrada: $url";
                break;
        }
    }
}
