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
        require Enlaces::VIEW_PATH . "clinica/login.php";
    }

    /********************* FORMULARIO REGISTRO *********************/
    public function loguear()
    {
        require Enlaces::VIEW_PATH . "clinica/loguear.php";
    }

    /********************* PROCESAR REGISTRO *********************/
    public function registrar()
    {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "clinica/loguear");
            exit;
        }

        // Sanitizar entrada
        $nombre     = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
        $direccion  = trim(filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING));
        $telefono   = trim(filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING));
        $email      = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $usuario    = trim(filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING));
        $pass1      = trim($_POST['password'] ?? '');
        $pass2      = trim($_POST['password2'] ?? '');

        // Validar contraseñas
         if ($pass1 !== $pass2) {
            die("Las contraseñas no coinciden.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear'>Volver</a>");
        }

        // Conexión BD
        $pdo = BaseDatos::getConexion();

        // Crear modelo
        $clinica = new Clinica($pdo);
        $clinica->setNombreClinica($nombre);
        $clinica->setDireccionClinica($direccion);
        $clinica->setTelefonoClinica($telefono);
        $clinica->setEmailClinica($email);
        $clinica->setUsuarioClinica($usuario);
        $clinica->setPasswordClinica($pass1);

        // Guardar
        $guardado = $clinica->guardarClinica($pdo);

        if (!$guardado) {
            die("Error al registrar la clínica.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear'>Volver</a>");
        }

        // Redirigir
        header("Location: " . Enlaces::BASE_URL . "clinica/login");
        exit;
    }

    /***************************** PROCESAR LOGIN  *************************************/
    public function acceder()
    {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "clinica/login");
            exit;
        }

        // Sanitizar entrada
        $usuario = trim(filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING));
        $password = trim($_POST['password'] ?? '');

        // Conexión BD
        $pdo = BaseDatos::getConexion();

        // Autenticar
        $clinica = new Clinica();
        $resultado = $clinica->autenticarClinica($pdo, $usuario, $password);

        if (!$resultado) {
            die("Usuario o contraseña incorrectos.<br><a href='" . Enlaces::BASE_URL . "clinica/login'>Volver</a>");
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

        header("Location: " . Enlaces::BASE_URL . "clinica/home");
        exit;
}

    /*************************  HOME CLINICA *************************/
    public function home()
    {
        session_start();

        // Verificar sesión
        if (!isset($_SESSION['clinica'])) {
            header("Location: " . Enlaces::BASE_URL . "clinica/login");
            exit;
        }

        require Enlaces::VIEW_PATH . "clinica/home.php";
    }

    /*************************  CERRAR SESIÓN *************************/
    public function logout(){
        session_start();
        session_unset();
        session_destroy();

        header("Location: " . Enlaces::BASE_URL . "clinica/login");
        exit;
    }
}
