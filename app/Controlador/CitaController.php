<?php
/**
 * Controlador del módulo Cita
 *
 * Gestiona la creación, edición, asignación y visualización
 * de citas para clínicas, médicos y pacientes.
 *
 * @package Mediagend\App\Controlador
 */

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Cita;

/**
 * Clase CitaController
 *
 * Controlador encargado de manejar la lógica de citas, incluyendo
 * la visualización de agendas, formularios, creación, modificación,
 * asignación y eliminación de citas.
 */
class CitaController
{
    /**
     * Muestra la agenda semanal de la clínica
     *
     * @return void
     */
    public function agendaClinica()
    {
        session_start();

        if (!isset($_SESSION['clinica'])) {
            exit('Acceso denegado');
        }

        $pdo = BaseDatos::getConexion();
        $citaModel = new Cita();

        $citas = $citaModel->mostrarPorClinica(
            $pdo,
            $_SESSION['clinica']['id_clinica']
        );

        require Enlaces::VIEW_PATH . 'cita/agenda_calendario.php';
    }

    /**
     * Muestra la agenda semanal del médico
     *
     * @return void
     */
    public function agendaMedico()
    {
        session_start();

        if (!isset($_SESSION['medico'])) {
            exit('Acceso denegado');
        }

        if (!isset($_SESSION['clinica'])) {
            exit('Acceso denegado');
        }

        $pdo = BaseDatos::getConexion();
        $citaModel = new Cita();

        $citas = $citaModel->mostrarPorMedico(
            $pdo,
            $_SESSION['medico']['id_medico']
        );

        require Enlaces::VIEW_PATH . 'cita/citas_medico.php';
    }

    /**
     * Muestra la agenda de un paciente
     *
     * @return void
     */
    public function agendaPaciente()
    {
        session_start();

        if (!isset($_SESSION['paciente']) && !isset($_SESSION['medico'])) {
            exit('Acceso denegado');
        }

        $pdo = BaseDatos::getConexion();
        $citaModel = new Cita();

        if (isset($_SESSION['paciente'])) {
            $idPaciente = (int) $_SESSION['paciente']['id_paciente'];
        } elseif (isset($_SESSION['medico']) && isset($_GET['id_paciente'])) {
            $idPaciente = (int) $_GET['id_paciente'];
        } else {
            exit('Paciente no especificado');
        }

        $citas = $citaModel->mostrarPorPaciente($pdo, $idPaciente);

        require Enlaces::VIEW_PATH . 'cita/citas_paciente.php';
    }

    /**
     * Muestra el formulario para crear una cita
     *
     * @return void
     */
    public function formCrear()
    {
        session_start();

        if (!isset($_SESSION['clinica'])) {
            exit('Acceso denegado');
        }

        $fecha = $_POST['fecha'];
        $hora  = $_POST['hora'];
        $idMedico = (int)$_POST['id_medico'];

        require Enlaces::VIEW_PATH . 'cita/crear_cita.php';
    }

    /**
     * Muestra el formulario para editar una cita existente
     *
     * @return void
     */
    public function formEditar()
    {
        session_start();

        if (!isset($_SESSION['clinica'])) {
            exit('Acceso denegado');
        }

        $id_cita = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id_cita) {
            exit('ID de cita inválido');
        }

        $pdo = BaseDatos::getConexion();
        $citaModel = new Cita();

        $cita = $citaModel->mostrarCita($pdo, $id_cita);

        if (!$cita) {
            exit('Cita no encontrada');
        }

        require Enlaces::VIEW_PATH . 'cita/editar_cita.php';
    }

    /**
     * Crea un hueco de cita pendiente
     *
     * @return void
     */
    public function crearHueco()
    {
        session_start();

        if (!isset($_SESSION['clinica'])) {
            exit('Acceso denegado');
        }

        $idMedico   = (int)$_POST['id_medico'];
        $idPaciente = !empty($_POST['id_paciente']) ? (int)$_POST['id_paciente'] : null;

        $cita = new Cita();
        $cita->setIdClinica($_SESSION['clinica']['id_clinica']);
        $cita->setIdMedico($idMedico);
        $cita->setFechaCita($_POST['fecha']);
        $cita->setHoraCita($_POST['hora']);
        $cita->setEstadoCita('pendiente');
        $cita->setIdPaciente($idPaciente);

        $pdo = BaseDatos::getConexion();
        $cita->guardarCita($pdo);

        header('Location: ' . Enlaces::BASE_URL . 'citas/ver_agenda');
        exit;
    }

    /**
     * Asigna un paciente a una cita
     *
     * @return void
     */
    public function asignarPaciente()
    {
        session_start();

        if (!isset($_SESSION['clinica'])) {
            exit('Acceso denegado');
        }

        $pdo = BaseDatos::getConexion();

        $cita = new Cita();
        $cita->setIdPaciente((int)$_POST['id_paciente']);
        $cita->setMotivoCita($_POST['motivo']);
        $cita->setEstadoCita('confirmada');

        $cita->actualizarAsignacion(
            $pdo,
            (int)$_POST['id_cita']
        );

        header('Location: ' . Enlaces::BASE_URL . 'citas');
    }

    /**
     * Modifica una cita existente
     *
     * @return void
     */
    public function modificar()
    {
        session_start();

        if (!isset($_SESSION['clinica'])) {
            exit('Acceso denegado');
        }

        $id_cita = filter_input(INPUT_POST, 'id_cita', FILTER_VALIDATE_INT);

        if (!$id_cita) {
            exit('ID de cita inválido');
        }

        $fecha  = $_POST['fecha']  ?? null;
        $hora   = $_POST['hora']   ?? null;
        $estado = $_POST['estado'] ?? null;
        $motivo = $_POST['motivo'] ?? null;

        if (!$fecha || !$hora || !$estado) {
            exit('Datos incompletos');
        }

        $pdo = BaseDatos::getConexion();
        $citaModel = new Cita();

        $citaModel->setFechaCita($fecha);
        $citaModel->setHoraCita($hora);
        $citaModel->setEstadoCita($estado);
        $citaModel->setMotivoCita($motivo);
        $citaModel->setIdInforme(null);

        $resultado = $citaModel->actualizarCita($pdo, $id_cita);

        if ($resultado === 'ERR_CITA_04') {
            exit('Error al actualizar la cita');
        }

        header('Location: ' . Enlaces::BASE_URL . 'citas/ver_agenda');
        exit;
    }

    /**
     * Muestra las citas del médico
     *
     * @return void
     */
    public function citasMedico()
    {
        session_start();

        if (!isset($_SESSION['medico'])) {
            exit('Acceso denegado');
        }

        $pdo = BaseDatos::getConexion();
        $citaModel = new Cita();

        $citas = $citaModel->mostrarPorMedico(
            $pdo,
            $_SESSION['medico']['id_medico']
        );

        require Enlaces::VIEW_PATH . 'citas/home_medico.php';
    }

    /**
     * Elimina una cita existente
     *
     * @return void
     */
    public function eliminar()
    {
        session_start();

        if (!isset($_SESSION['clinica'])) {
            exit('Acceso denegado');
        }

        $id_cita = filter_input(INPUT_POST, 'id_cita', FILTER_VALIDATE_INT);

        if (!$id_cita) {
            exit('ID de cita inválido');
        }

        $pdo = BaseDatos::getConexion();
        $citaModel = new Cita();

        $resultado = $citaModel->eliminarCita($pdo, $id_cita);

        header('Location: ' . Enlaces::BASE_URL . 'citas/ver_agenda');
        exit;
    }
}
