<?php

/**
 * Clase Rutas
 *
 * Gestiona las rutas del sistema y llama al controlador
 * correspondiente según la URL solicitada.
 *
 * Cada ruta corresponde a un controlador y método específico,
 * permitiendo separar la lógica de negocio según el tipo de usuario
 * (Administrador, Clínica, Paciente, Médico) o funcionalidad
 * (Informes, Citas, Lobby, etc.).
 *
 * @package Mediagend\App\Rutas
 */

namespace Mediagend\App\Rutas;

use Mediagend\App\Controlador\LobbyController;
use Mediagend\App\Controlador\AdminController;
use Mediagend\App\Controlador\ClinicaController;
use Mediagend\App\Controlador\PacienteController;
use Mediagend\App\Controlador\MedicoController;
use Mediagend\App\Controlador\InformeController;
use Mediagend\App\Controlador\CitaController;

/**
 * Clase Rutas
 *
 * Gestiona las rutas del sistema y llama al controlador
 * correspondiente según la URL solicitada.
 *
 * Cada ruta corresponde a un controlador y método específico,
 * permitiendo separar la lógica de negocio según el tipo de usuario
 * (Administrador, Clínica, Paciente, Médico) o funcionalidad
 * (Informes, Citas, Lobby, etc.).
 *
 * @package Mediagend\App\Rutas
 */
class Rutas
{
    /**
     * Procesa la URL actual y redirige al controlador y método correspondiente.
     *
     * Este método obtiene el parámetro 'url' de la solicitud GET
     * y realiza un switch para determinar qué controlador y método
     * ejecutar. Si la URL no coincide con ninguna ruta definida,
     * se llama al método error404 del LobbyController.
     *
     * Rutas definidas incluyen:
     * 
     * ---------- LOBBY ----------
     * - 'lobby/index' => LobbyController::index()
     * 
     * ---------- ADMINISTRADOR ----------
     * - 'admin/login_admin' => AdminController::login()
     * - 'admin/home_admin' => AdminController::home()
     * - 'admin/loguear_admin' => AdminController::loguear()
     * - 'admin/registrar' => AdminController::registrar()
     * - 'admin/acceder' => AdminController::acceder()
     * - 'admin/logout' => AdminController::logout()
     * - 'admin/home/clinicas' => AdminController::home_clinicas()
     * - 'admin/home/insertar' => AdminController::home_insertar()
     * 
     * ---------- CLINICA ----------
     * - 'clinica/login_clinica' => ClinicaController::login()
     * - 'clinica/home_clinica' => ClinicaController::home()
     * - 'clinica/registrar_clinica' => ClinicaController::registrar()
     * - 'clinica/loguear_clinica' => ClinicaController::loguear()
     * - 'clinica/acceder' => ClinicaController::acceder()
     * - 'clinica/modificar' => ClinicaController::modificar()
     * - 'clinica/eliminar' => ClinicaController::eliminar()
     * - 'clinica/logout' => ClinicaController::logout()
     * - 'clinica/home/pacientes' => ClinicaController::home_pacientes()
     * - 'clinica/home/insertar' => AdminController::home_insertar()
     * - 'clinica/home/medicos' => ClinicaController::home_medicos()
     * 
     * ---------- PACIENTE ----------
     * - 'paciente/login_paciente' => PacienteController::login()
     * - 'paciente/home_paciente' => PacienteController::home()
     * - 'paciente/loguear_paciente' => PacienteController::loguear()
     * - 'paciente/registrar_paciente' => PacienteController::registrar()
     * - 'paciente/acceder' => PacienteController::acceder()
     * - 'paciente/modificar' => PacienteController::modificar()
     * - 'paciente/modificar_mis_datos' => PacienteController::modificar_mis_datos()
     * - 'paciente/modificar_password' => PacienteController::modificar_password()
     * - 'paciente/eliminar' => PacienteController::eliminar()
     * - 'paciente/logout' => PacienteController::logout()
     * - 'paciente/home/citas' => PacienteController::home_mis_citas()
     * - 'paciente/home/informes' => PacienteController::home_mis_informes()
     * - 'paciente/home/ajustes' => PacienteController::home_mis_ajustes()
     * - 'paciente/home/inicio' => PacienteController::home_inicio()
     * 
     * ---------- MÉDICO ----------
     * - 'medico/login_medico' => MedicoController::login()
     * - 'medico/home_medico' => MedicoController::home()
     * - 'medico/loguear_medico' => MedicoController::loguear()
     * - 'medico/registrar_medico' => MedicoController::registrar()
     * - 'medico/acceder' => MedicoController::acceder()
     * - 'medico/modificar' => MedicoController::modificar()
     * - 'medico/eliminar' => MedicoController::eliminar()
     * - 'medico/logout' => MedicoController::logout()
     * - 'medico/home/pacientes' => MedicoController::home_mis_pacientes()
     * 
     * ---------- INFORMES ----------
     * - 'informe/crear' => InformeController::crear()
     * - 'informe/guardar' => InformeController::guardar()
     * - 'informe/listar' => InformeController::listar()
     * - 'informe/ver' => InformeController::ver()
     * - 'informe/eliminar' => InformeController::eliminar()
     * 
     * ---------- CITAS ----------
     * - 'citas/form_editar' => CitaController::formEditar()
     * - 'citas/modificar' => CitaController::modificar()
     * - 'citas/crear_hueco' => CitaController::crearHueco()
     * - 'citas/asignar_paciente' => CitaController::asignarPaciente()
     * - 'citas/pacientes' => CitaController::agendaPaciente()
     * - 'citas/ver_agenda' => CitaController::agendaClinica()
     * - 'citas/ver_agenda_medico' => CitaController::agendaMedico()
     * - 'citas/form_crear' => CitaController::formCrear()
     * - 'citas/eliminar' => CitaController::eliminar()
     * 
     * ---------- ERROR ----------
     * - Cualquier otra URL => LobbyController::error404()
     *
     * @return void
     */


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

            case 'admin/logout':
                $controller = new AdminController();
                $controller->logout();
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

            case 'clinica/loguear_clinica':
                $controller = new ClinicaController();
                $controller->loguear();
                break;

            case 'clinica/acceder':
                $controller = new ClinicaController();
                $controller->acceder();
                break;

            case 'clinica/modificar':
                $controller = new ClinicaController();
                $controller->modificar();
                break;

            case 'clinica/eliminar':
                $controller = new ClinicaController();
                $controller->eliminar();
                break;

            case 'clinica/logout':
                $controller = new ClinicaController();
                $controller->logout();
                break;

            /****************   VISTAS CLINICA HOME ****************/

            case 'clinica/home/pacientes':
                $controller = new ClinicaController();
                $controller->home_pacientes();
                break;

            case 'clinica/home/insertar':
                $controller = new AdminController();
                $controller->home_insertar();
                break;

            case 'clinica/home/medicos':
                $controller = new ClinicaController();
                $controller->home_medicos();
                break;


            // ---------- PACIENTE ---------- //
            //***********************************************************************************************//
            case 'paciente/login_paciente':
                $controller = new PacienteController();
                $controller->login();
                break;

            case 'paciente/home_paciente':
                $controller = new PacienteController();
                $controller->home();
                break;

            case 'paciente/loguear_paciente':
                $controller = new PacienteController();
                $controller->loguear();
                break;

            case 'paciente/registrar_paciente':
                $controller = new PacienteController();
                $controller->registrar();
                break;

            case 'paciente/acceder':
                $controller = new PacienteController();
                $controller->acceder();
                break;

            case 'paciente/modificar':
                $controller = new PacienteController();
                $controller->modificar();
                break;

            case 'paciente/modificar_mis_datos':
                $controller = new PacienteController();
                $controller->modificar_mis_datos();
                break;

            case 'paciente/modificar_password':
                $controller = new PacienteController();
                $controller->modificar_password();
                break;

            case 'paciente/eliminar':
                $controller = new PacienteController();
                $controller->eliminar();
                break;

            case 'paciente/logout':
                $controller = new PacienteController();
                $controller->logout();
                break;

            /****************   VISTAS PACIENTE HOME ****************/
            case 'paciente/home/citas':
                $controller = new PacienteController();
                $controller->home_mis_citas();
                break;

            case 'paciente/home/informes':
                $controller = new PacienteController();
                $controller->home_mis_informes();
                break;

            case 'paciente/home/ajustes':
                $controller = new PacienteController();
                $controller->home_mis_ajustes();
                break;

            case 'paciente/home/inicio':
                $controller = new PacienteController();
                $controller->home_inicio();
                break;

            // ---------- MÉDICO ---------- //
            //***********************************************************************************************//
            case 'medico/login_medico':
                $controller = new MedicoController();
                $controller->login();
                break;

            case 'medico/home_medico':
                $controller = new MedicoController();
                $controller->home();
                break;

            case 'medico/loguear_medico':
                $controller = new MedicoController();
                $controller->loguear();
                break;

            case 'medico/registrar_medico':
                $controller = new MedicoController();
                $controller->registrar();
                break;

            case 'medico/acceder':
                $controller = new MedicoController();
                $controller->acceder();
                break;

            case 'medico/modificar':
                $controller = new MedicoController();
                $controller->modificar();
                break;

            case 'medico/eliminar':
                $controller = new MedicoController();
                $controller->eliminar();
                break;

            case 'medico/logout':
                $controller = new MedicoController();
                $controller->logout();
                break;

            /****************   VISTAS MÉDICO HOME ****************/

            case 'medico/home/pacientes':
                $controller = new MedicoController();
                $controller->home_mis_pacientes();
                break;

            /************************* INFORMES ***********************/

            case 'informe/crear':
                $controller = new InformeController();
                $controller->crear();
                break;

            case 'informe/guardar':
                $controller = new InformeController();
                $controller->guardar();
                break;

            case 'informe/listar':
                $controller = new InformeController();
                $controller->listar();
                break;

            case 'informe/ver':
                $controller = new InformeController();
                $controller->ver();
                break;

            case 'informe/eliminar':
                $controller = new InformeController();
                $controller->eliminar();
                break;

            /****************************** CITAS ******************************/

                /* case 'citas/indice':
                $controller = new CitaController();
                $controller->index();
                break; */

            case 'citas/form_editar':
                $controller = new CitaController();
                $controller->formEditar();
                break;

            case 'citas/modificar':
                $controller = new CitaController();
                $controller->modificar();
                break;

            case 'citas/crear_hueco':
                $controller = new CitaController();
                $controller->crearHueco();
                break;

            case 'citas/asignar_paciente':
                $controller = new CitaController();
                $controller->asignarPaciente();
                break;

            case 'citas/pacientes':
                $controller = new CitaController();
                $controller->agendaPaciente();
                break;

            case 'citas/ver_agenda':
                $controller = new CitaController();
                $controller->agendaClinica();
                break;

            case 'citas/ver_agenda_medico':
                $controller = new CitaController();
                $controller->agendaMedico();
                break;

            case 'citas/form_crear':
                $controller = new CitaController();
                $controller->formCrear();
                break;

            case 'citas/eliminar':
                $controller = new CitaController();
                $controller->eliminar();
                break;

            // ---------- ERROR ---------- //
            //***********************************************************************************************//
            default:
                $controller = new LobbyController();
                $controller->error404();
                break;
        }
    }
}
