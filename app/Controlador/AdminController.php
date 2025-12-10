<?php
namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Administrador;
use Mediagend\App\Config\BaseDatos;

class AdminController
{

    /** ============================================================
     *  FORMULARIO LOGIN
     *  ============================================================ */
    public function login()
    {
        require Enlaces::VIEW_PATH . "admin/login.php";
    }


    /** ============================================================
     *  FORMULARIO REGISTRO
     *  ============================================================ */
    public function loguear()
    {
        require Enlaces::VIEW_PATH . "admin/loguear.php";
    }


    /** ============================================================
     *  PROCESAR REGISTRO
     *  ============================================================ */
    public function registrar()
    {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "admin/loguear");
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
            die("❌ Las contraseñas no coinciden.<br><a href='" . Enlaces::BASE_URL . "admin/loguear'>Volver</a>");
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
            die("❌ Error al registrar el administrador.<br><a href='" . Enlaces::BASE_URL . "admin/loguear'>Volver</a>");
        }

        // Redirigir
        header("Location: " . Enlaces::BASE_URL . "admin/login");
        exit;
    }


    /** ============================================================
     *  PROCESAR LOGIN
     *  ============================================================ */
    public function acceder()
    {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "admin/login");
            exit;
        }

        $usuario = trim($_POST['usuario'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $pdo = BaseDatos::getConexion();

        $admin = new Administrador();
        $resultado = $admin->autenticarAdmin($pdo, $usuario, $password);

        if (!$resultado) {
            echo "❌ Credenciales incorrectas<br>";
            echo "<a href='" . Enlaces::BASE_URL . "admin/login'>Volver</a>";
            exit;
        }

        // Guardar sesión
        $_SESSION['admin'] = [
            "id_admin"  => $resultado['id_admin'],
            "nombre"    => $resultado['nombre_admin'],
            "email"     => $resultado['email_admin']
        ];

        header("Location: " . Enlaces::BASE_URL . "admin/home");
        exit;
    }


    /** ============================================================
     *  HOME ADMIN
     *  ============================================================ */
    public function home()
    {
        session_start();
        if (!isset($_SESSION['admin'])) {
            header("Location: " . Enlaces::BASE_URL . "admin/login");
            exit;
        }

        require Enlaces::VIEW_PATH . "admin/home.php";
    }


    /** ============================================================
     *  LOGOUT
     *  ============================================================ */
    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: " . Enlaces::BASE_URL . "admin/login");
        exit;
    }
}

