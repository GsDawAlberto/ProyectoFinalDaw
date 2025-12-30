<?php

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Administrador;
use Mediagend\App\Config\BaseDatos;

class AdminController
{
    /**********************   RUTA DE VISTAS ***********************************/

    public function home_clinicas()
    {
        require Enlaces::VIEW_CONTENT_ADMIN_PATH . "clinicas.php";
    }

    public function home_configuracion()
    {
        require Enlaces::VIEW_CONTENT_ADMIN_PATH . "configuracion.php";
    }

    public function home_insertar()
    {
        require Enlaces::VIEW_CONTENT_ADMIN_PATH . "insertar.php";
    }

    /********************  FORMULARIO LOGIN *******************/
    public function login()
    {
        require Enlaces::VIEW_PATH . "admin/login_admin.php";
    }


    /******************** FORMULARIO REGISTRO *******************/
    public function loguear()
    {
        require Enlaces::VIEW_PATH . "admin/loguear_admin.php";
    }


    /********************* PROCESAR REGISTRO *********************/
    public function registrar()
    {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "admin/loguear_admin");
            exit;
        }

        // Sanitizar entrada
        $nombre     = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
        $usuario    = trim(filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING));
        $email      = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $pass1      = trim($_POST['password'] ?? '');
        $pass2      = trim($_POST['password_2'] ?? '');

        // Validar contraseñas
        if ($pass1 !== $pass2) {
            die("Las contraseñas no coinciden.<br><a href='" . Enlaces::BASE_URL . "admin/loguear_admin'>Volver</a>");
        }

        // Conexión BD
        $pdo = BaseDatos::getConexion();

        // Crear modelo
        $admin = new Administrador();
        $admin->setNombreAdmin($nombre);
        $admin->setUsuarioAdmin($usuario);
        $admin->setEmailAdmin($email);
        $admin->setPasswordAdmin($pass1);

        // Guardar
        $guardado = $admin->guardarAdmin($pdo);

        if (!$guardado) {
            die("Error al registrar el administrador.<br><a href='" . Enlaces::BASE_URL . "admin/loguear_admin'>Volver</a>");
        }

        // Redirigir
        header("Location: " . Enlaces::BASE_URL . "admin/login_admin");
        exit;
    }


    /********************************* PROCESAR LOGIN *********************************/
    public function acceder()
    {

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "admin/login_admin");
            exit;
        }

        // Sanitizar entrada
        $usuario = trim(filter_input(INPUT_POST, 'usuario_admin', FILTER_SANITIZE_STRING));
        $password = trim($_POST['password_admin'] ?? '');

        // Conexión BD
        $pdo = BaseDatos::getConexion();

        // Autenticar
        $admin = new Administrador();
        $resultado = $admin->autenticarAdmin($pdo, $usuario, $password);

        if (!$resultado) {
            echo "Credenciales incorrectas<br>";
            echo "<a href='" . Enlaces::BASE_URL . "admin/login_admin'>Volver</a>";
            exit;
        }

        session_start();

        // Guardar sesión
        $_SESSION['admin'] = [
            'id_admin'       => $resultado['id_admin'],
            'nombre_admin'   => $resultado['nombre_admin'],
            'usuario_admin'  => $resultado['usuario_admin'],
            'email_admin'    => $resultado['email_admin']
        ];

        header("Location: " . Enlaces::BASE_URL . "admin/home_admin");
        exit;
    }


    /*************************  HOME ADMINISTRADOR *************************/
    public function home()
    {
        session_start();
        if (!isset($_SESSION['admin'])) {
            header("Location: " . Enlaces::BASE_URL . "admin/login_admin");
            exit;
        }

        require Enlaces::VIEW_PATH . "admin/home_admin.php";
    }


    /************************* CERRAR SESIÓN ******************************/
    public function logout()
    {
        session_start(); // Reanudar sesión
        session_unset(); // Eliminar todas las variables de sesión
        session_destroy(); // Destruir la sesión
        header("Location: " . Enlaces::BASE_URL . "admin/login_admin");
        exit;
    }

    /************************* GUARDAR CONFIGURACIÓN ADMIN ******************************/
    /* public function guardar_configuracion()
{
    session_start();

    $id_admin = $_SESSION['admin']['id_admin'];

    $data = [
        'tema'   => $_POST['tema'],
        'fuente' => $_POST['fuente'],
        'color'  => $_POST['color']
    ];

    $pdo = BaseDatos::getConexion();
    $config = new Administrador();
    $config->guardarConfiguracionAdmin($pdo, $id_admin, $data);

    // Guardar en sesión para aplicar instantáneo
    $_SESSION['config'] = $data;

    header("Location: " . Enlaces::BASE_URL . "admin/home/configuracion");
} */
}
