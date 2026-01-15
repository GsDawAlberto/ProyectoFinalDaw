<?php

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Cita;

class CitaController
{
    public function index() // Ver agenda (home_clinica)
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

        require Enlaces::VIEW_PATH . 'citas/home_clinica.php';
    }
    public function crearHueco() // Crear hueco
    {
        session_start();

        if (!isset($_SESSION['clinica'])) {
            exit('Acceso denegado');
        }

        $cita = new Cita();
        $cita->setIdClinica($_SESSION['clinica']['id_clinica']);
        $cita->setIdMedico((int)$_POST['id_medico']);
        $cita->setFechaCita($_POST['fecha']);
        $cita->setHoraCita($_POST['hora']);
        $cita->setEstadoCita('pendiente');
        $cita->setIdPaciente(null);

        $pdo = BaseDatos::getConexion();
        $cita->guardarCita($pdo);

        header('Location: ' . Enlaces::BASE_URL . 'citas');
    }
    public function asignarPaciente() // Asignar paciente
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
    public function cambiarEstado()  // Confirmar / cancelar cita
    {
        session_start();

        if (!isset($_SESSION['medico'])) {
            exit('Acceso denegado');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . Enlaces::BASE_URL . 'medico/home');
            exit;
        }

        $id_cita = filter_input(INPUT_POST, 'id_cita', FILTER_VALIDATE_INT);
        $estado  = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);

        $estadosPermitidos = ['pendiente', 'confirmada', 'cancelada', 'realizada'];

        if (!$id_cita || !in_array($estado, $estadosPermitidos)) {
            exit('Datos inválidos');
        }

        $pdo = BaseDatos::getConexion();
        $citaModel = new Cita();

        // Recuperamos la cita
        $cita = $citaModel->mostrarCita($pdo, $id_cita);

        if (!$cita) {
            exit('Cita no encontrada');
        }

        // 🔒 Regla lógica: no cambiar si está cancelada o realizada
        if (in_array($cita['estado_cita'], ['cancelada', 'realizada'])) {
            exit('La cita no puede modificarse');
        }

        // Actualizamos estado
        $citaModel->setEstadoCita($estado);
        $resultado = $citaModel->actualizarCita($pdo, $id_cita);

        if ($resultado === 'ERR_CITA_04') {
            exit('Error al actualizar la cita');
        }

        header('Location: ' . Enlaces::BASE_URL . 'medico/citas');
        exit;
    }

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
}
