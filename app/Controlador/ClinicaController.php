<?php

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Clinica;
use Mediagend\App\Config\BaseDatos;

class ClinicaController
{
    /******************** RUTA DE VISTAS **************************/

    /********************* FORMULARIO LOGIN ***********************/

    public function login()
    {
        require Enlaces::VIEW_PATH . "clinica/login_clinica.php";
    }

    /********************* FORMULARIO REGISTRO *********************/
    public function loguear()
    {
        require Enlaces::VIEW_PATH . "clinica/loguear_clinica.php";
    }

    /********************* PROCESAR REGISTRO *********************/
    public function registrar()
    {
        session_start();

        if (!isset($_SESSION['admin'])) {
            header("Location: " . Enlaces::BASE_URL . "admin/login_admin");
            exit;
        }

        $idAdmin = $_SESSION['admin']['id_admin'];

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "clinica/loguear_clinica");
            exit;
        }

        // Sanitizar entrada
        $nombre     = trim(filter_input(INPUT_POST, 'nombre_clinica', FILTER_SANITIZE_STRING));
        $direccion  = trim(filter_input(INPUT_POST, 'direccion_clinica', FILTER_SANITIZE_STRING));
        $telefono   = trim(filter_input(INPUT_POST, 'telefono_clinica', FILTER_SANITIZE_STRING));
        $email      = trim(filter_input(INPUT_POST, 'email_clinica', FILTER_SANITIZE_EMAIL));
        $usuario    = trim(filter_input(INPUT_POST, 'usuario_clinica', FILTER_SANITIZE_STRING));
        $pass1      = trim($_POST['password_clinica'] ?? '');
        $pass2      = trim($_POST['password2_clinica'] ?? '');

        // Validar contraseñas
        if ($pass1 !== $pass2) {
            die("Las contraseñas no coinciden.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
        }

        // Conexión BD
        $pdo = BaseDatos::getConexion();

        // Crear modelo
        $clinica = new Clinica();
        $clinica->setIdAdmin($idAdmin);
        $clinica->setNombreClinica($nombre);
        $clinica->setDireccionClinica($direccion);
        $clinica->setTelefonoClinica($telefono);
        $clinica->setEmailClinica($email);
        $clinica->setUsuarioClinica($usuario);
        $clinica->setPasswordClinica($pass1);

        // Guardar
        $guardado = $clinica->guardarClinica($pdo);

        if (!$guardado) {
            die("Error al registrar la clínica.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
        }

        // Redirigir
        header("Location: " . Enlaces::BASE_URL . "admin/home/clinicas");
        exit;
    }

    /***************************** PROCESAR LOGIN  *************************************/
    public function acceder()
    {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "clinica/login_clinica");
            exit;
        }

        // Sanitizar entrada
        $usuario = trim(filter_input(INPUT_POST, 'usuario_clinica', FILTER_SANITIZE_STRING));
        $password = trim($_POST['password_clinica'] ?? '');

        // Conexión BD
        $pdo = BaseDatos::getConexion();

        // Autenticar
        $clinica = new Clinica();
        $resultado = $clinica->autenticarClinica($pdo, $usuario, $password);

        if (!$resultado) {
            echo "Credenciales incorrectas<br>";
            echo "<a href='" . Enlaces::BASE_URL . "clinica/login_clinica'>Volver</a>";
            exit;
        }

        session_Start();

        // Guardar sesión
        $_SESSION['clinica'] = [
            'id'       => $resultado['id_clinica'],
            'nombre'   => $resultado['nombre_clinica'],
            'usuario'  => $resultado['usuario_clinica'],
            'email'    => $resultado['email_clinica']
        ];

        header("Location: " . Enlaces::BASE_URL . "clinica/home_clinica");
        exit;
    }

    /*************************  HOME CLINICA *************************/
    public function home()
    {
        session_start();

        // Verificar sesión
        if (!isset($_SESSION['clinica'])) {
            header("Location: " . Enlaces::BASE_URL . "clinica/login_clinica");
            exit;
        }

        require Enlaces::VIEW_PATH . "clinica/home_clinica.php";
    }

    /*************************  CERRAR SESIÓN *************************/
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header("Location: " . Enlaces::BASE_URL . "clinica/login_clinica");
        exit;
    }
}
