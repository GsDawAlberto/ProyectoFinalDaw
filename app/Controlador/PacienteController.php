<?php

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Paciente;
use Mediagend\App\Config\BaseDatos;

class PacienteController
{
    /**************************** RUTA DE VISTAS **************************/

    /**************************** FORMULARIO LOGIN *************************/
    public function login()
    {
        require Enlaces::VIEW_PATH . "paciente/login_paciente.php";
    }

    /**************************** FORMULARIO REGISTRO *************************/
    public function loguear()
    {
        require Enlaces::VIEW_PATH . "paciente/loguear_paciente.php";
    }

    /**************************** PROCESAR REGISTRO *************************/
    public function registrar()
    {
        session_start();

        if (!isset($_SESSION['clinica'])) {
            header("Location: " . Enlaces::BASE_URL . "clinica/login_clinica");
            exit;
        }

        $idClinica = $_SESSION['clinica']['id_clinica'];

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "paciente/loguear_paciente");
            exit;
        }

        // Sanitizar entrada
        $nombre     = trim(filter_input(INPUT_POST, 'nombre_paciente', FILTER_SANITIZE_STRING));
        $apellidos  = trim(filter_input(INPUT_POST, 'apellidos_paciente', FILTER_SANITIZE_STRING));
        $dni        = trim(filter_input(INPUT_POST, 'dni_paciente', FILTER_SANITIZE_STRING));
        $telefono   = trim(filter_input(INPUT_POST, 'telefono_paciente', FILTER_SANITIZE_STRING));
        $email      = trim(filter_input(INPUT_POST, 'email_paciente', FILTER_SANITIZE_EMAIL));
        $usuario    = trim(filter_input(INPUT_POST, 'usuario_paciente', FILTER_SANITIZE_STRING));
        $fotoRuta = trim($_FILES['foto_paciente']['name']);
        $pass1      = trim($_POST['password_paciente'] ?? '');
        $pass2      = trim($_POST['password2_paciente'] ?? '');

        // Validar contraseñas
        if ($pass1 !== $pass2) {
            die("Las contraseñas no coinciden.<br><a href='" . Enlaces::BASE_URL . "paciente/loguear_paciente'>Volver</a>");
        }

        // Conexión BD
        $pdo = BaseDatos::getConexion();

        // Crear modelo
        $paciente = new Paciente();
        $paciente->setIdClinica($idClinica);
        $paciente->setNombrePaciente($nombre);
        $paciente->setApellidosPaciente($apellidos);
        $paciente->setDniPaciente($dni);
        $paciente->setTelefonoPaciente($telefono);
        $paciente->setEmailPaciente($email);
        $paciente->setUsuarioPaciente($usuario);
        $paciente->setFotoPaciente($fotoRuta);
        $paciente->setPasswordPaciente($pass1);


        //Guardar
        $guardado = $paciente->guardarPaciente($pdo);

        if (!$guardado) {
            die("Error al registrar el paciente.<br><a href='" . Enlaces::BASE_URL . "paciente/loguear_paciente'>Volver</a>");
        }

        // Redirigir a home de clinica
        header("Location: " . Enlaces::BASE_URL . "clinica/home/pacientes");
        exit;
    }

    /*********************************** PROCESAR LOGIN *************************************/
    public function acceder()
    {
        //Sanitizar entrada
        $usuario = trim(filter_input(INPUT_POST, 'usuario_paciente', FILTER_SANITIZE_STRING) ?? '');
        $password = trim(filter_input(INPUT_POST, 'password_paciente', FILTER_SANITIZE_STRING) ?? '');

        //Conexión BD
        $pdo = BaseDatos::getConexion();

        //Autenticar
        $paciente = new Paciente();
        $resultado = $paciente->autenticarPaciente($pdo, $usuario, $password);

        if (!$resultado) {
            echo "Error al autenticar el paciente.<br> ";
            echo "<a href='" . Enlaces::BASE_URL . "paciente/login_paciente'>Volver</a>";
            exit;
        }

        session_start();

        //Guardar sesión
        $_SESSION['paciente'] = [
            'id_paciente' => $resultado['id_paciente'],
            'nombre'      => $resultado['nombre_paciente'],
            'apellidos'   => $resultado['apellidos_paciente'],
            'dni'         => $resultado['dni_paciente'],
            'telefono'    => $resultado['telefono_paciente'],
            'email'       => $resultado['email_paciente'],
            'usuario'     => $resultado['usuario_paciente']
        ];

        header("Location: " . Enlaces::BASE_URL . "paciente/home_paciente");
        exit;
    }

    /*************************  HOME PACIENTE *************************/
    public function home()
    {
        session_start();

        if (!isset($_SESSION['paciente'])) {
            header("Location: " . Enlaces::BASE_URL . "paciente/login_paciente");
            exit;
        }

        require Enlaces::VIEW_PATH . "paciente/home_paciente.php";
    }

    /*************************  CERRAR SESIÓN *************************/
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header("Location: " . Enlaces::BASE_URL . "paciente/login_paciente");
        exit;
    }

    /******************************* ELIMINAR PACIENTE ***********************************/
    public function eliminar()
    {
        session_start();
        //Verificar sesión clinica
        if (!isset($_SESSION['clinica'])) {
            header("Location: " . Enlaces::BASE_URL . "clinica/login_clinica");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . Enlaces::BASE_URL . "clinica/home/pacientes");
            exit;
        }

        $id_paciente = filter_input(INPUT_POST, 'id_paciente', FILTER_VALIDATE_INT);
        if (!$id_paciente) {
            die("ID de paciente inválido");
        }

        $pdo = BaseDatos::getConexion();
        $pacienteModel = new Paciente();

        //Obtener paciente para comprobar propietario
        $paciente = $pacienteModel->mostrarPaciente($pdo, $id_paciente);

        if (!$paciente) {
            die("El paciente no existe");
        }

        //Seguridad: solo la clinica creadora puede borrar
        if ((int)$paciente['id_clinica'] !== (int)$_SESSION['clinica']['id_clinica']) {
            die("No tienes permisos para eliminar este paciente");
        }

        //Eliminar
        if (!$pacienteModel->eliminarPaciente($pdo, $id_paciente)) {
            die("Error al eliminar el paciente");
        }

        header("Location: " . Enlaces::BASE_URL . "clinica/home/pacientes");
        exit;
    }
}
