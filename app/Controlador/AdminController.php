<?php
/**
 * Controlador del módulo Administrador
 *
 * Gestiona las acciones relacionadas con el administrador:
 * vistas, registro, autenticación, sesión y cierre de sesión.
 *
 * @package Mediagend\App\Controlador
 */

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Administrador;
use Mediagend\App\Config\BaseDatos;

/**
 * Clase AdminController
 *
 * Controlador encargado de manejar las acciones del administrador,
 * incluyendo la carga de vistas, el registro, el login y la gestión
 * de la sesión.
 */
class AdminController
{
    /***********************************************   RUTA DE VISTAS ***********************************/

    /**
     * Muestra la vista de gestión de clínicas
     *
     * @return void
     */
    public function home_clinicas(): void
    {
        require Enlaces::VIEW_CONTENT_ADMIN_PATH . "clinicas.php";
    }

    /**
     * Muestra la vista de configuración del administrador
     *
     * @return void
     */
    public function home_configuracion(): void
    {
        require Enlaces::VIEW_CONTENT_ADMIN_PATH . "configuracion.php";
    }

    /**
     * Muestra la vista de inserción de datos
     *
     * @return void
     */
    public function home_insertar(): void
    {
        require Enlaces::VIEW_CONTENT_ADMIN_PATH . "insertar.php";
    }

    /************************************************  FORMULARIOS **********************************************/

    /**
     * Muestra la vista de login del administrador
     *
     * @return void
     */
    public function login(): void
    {
        require Enlaces::VIEW_PATH . "admin/login_admin.php";
    }

    /**
     * Muestra la vista de registro (loguear) del administrador
     *
     * @return void
     */
    public function loguear(): void
    {
        require Enlaces::VIEW_PATH . "admin/loguear_admin.php";
    }

    /********************************************** PROCESAR REGISTRO *************************************************/

    /**
     * Procesa el registro de un nuevo administrador
     *
     * Valida los datos recibidos por POST, crea el administrador
     * y lo guarda en la base de datos.
     *
     * @return never Finaliza la ejecución mediante redirección o die()
     */
    public function registrar()
    {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "admin/loguear_admin");
            exit;
        }

        // Sanitizar entrada
        $nombre     = trim(filter_input(INPUT_POST, 'nombre_admin', FILTER_SANITIZE_STRING));
        $apellidos  = trim(filter_input(INPUT_POST, 'apellidos_admin', FILTER_SANITIZE_STRING));
        $dni        = strtoupper(trim(filter_input(INPUT_POST, 'dni_admin', FILTER_SANITIZE_STRING)));
        $usuario    = trim(filter_input(INPUT_POST, 'usuario_admin', FILTER_SANITIZE_STRING));
        $email      = trim(filter_input(INPUT_POST, 'email_admin', FILTER_SANITIZE_EMAIL));
        $pass1      = trim($_POST['password'] ?? '');
        $pass2      = trim($_POST['password_2'] ?? '');

        // Validaciones
        if (strlen($nombre) < 3 || strlen($nombre) > 20) {
            die("El nombre debe tener entre 3 y 20 caracteres.<br><a href='" . Enlaces::BASE_URL . "admin/loguear_admin'>Volver</a>");
        }

        if (strlen($apellidos) < 10 || strlen($apellidos) > 50) {
            die("Los apellidos debe tener entre 10 y 50 caracteres.<br><a href='" . Enlaces::BASE_URL . "admin/loguear_admin'>Volver</a>");
        }

        if (strlen($dni) != 9) {
            die("El dni debe contener un formato valido, 8 caracteres seguido de una letra.<br><a href='" . Enlaces::BASE_URL . "admin/loguear_admin'>Volver</a>");
        }

        if (strlen($usuario) < 3 || strlen($usuario) > 15) {
            die("El usuario debe tener entre 3 y 15 caracteres.<br><a href='" . Enlaces::BASE_URL . "admin/loguear_admin'>Volver</a>");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("El email no es válido.<br><a href='" . Enlaces::BASE_URL . "admin/loguear_admin'>Volver</a>");
        }

        if ($pass1 !== $pass2 || strlen($pass1) < 6) {
            die("Las contraseñas no coinciden o son demasiado cortas (mínimo 6 caracteres).<br><a href='" . Enlaces::BASE_URL . "admin/loguear_admin'>Volver</a>");
        }

        // Conexión BD
        $pdo = BaseDatos::getConexion();

        // Crear modelo
        $admin = new Administrador();
        $admin->setNombreAdmin($nombre);
        $admin->setApellidosAdmin($apellidos);
        $admin->setDniAdmin($dni);
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

    /************************************************* PROCESAR ACCESO A LOGIN ********************************************************/

    /**
     * Procesa el acceso (login) del administrador
     *
     * Valida credenciales, autentica al usuario y crea la sesión.
     *
     * @return void
     */
    public function acceder(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "admin/login_admin");
            exit;
        }

        // Sanitizar entrada
        $usuario = trim(filter_input(INPUT_POST, 'usuario_admin', FILTER_SANITIZE_STRING));
        $password = trim($_POST['password_admin'] ?? '');

        // Validaciones

        if ($usuario === '' || $password === '') {
            die("Todos los campos son obligatorios.<br><a href='" . Enlaces::BASE_URL . "admin/login_admin'>Volver</a>");
        }

        if (strlen($usuario) < 3 || strlen($usuario) > 15) {
            die("El usuario debe tener entre 3 y 15 caracteres.<br><a href='" . Enlaces::BASE_URL . "admin/login_admin'>Volver</a>");
        }

        if (strlen($password) < 6) {
            die("La contraseña debe tener al menos 6 caracteres.<br><a href='" . Enlaces::BASE_URL . "admin/login_admin'>Volver</a>");
        }

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
            'id_admin'          => $resultado['id_admin'],
            'nombre_admin'      => $resultado['nombre_admin'],
            'apellidos_admin'   => $resultado['apellidos_admin'],
            'dni_admin'         => $resultado['dni_admin'],
            'usuario_admin'     => $resultado['usuario_admin'],
            'email_admin'       => $resultado['email_admin']
        ];

        header("Location: " . Enlaces::BASE_URL . "admin/home_admin");
        exit;
    }

    /*******************************************  HOME ADMINISTRADOR *****************************************************/

    /**
     * Muestra la vista principal del administrador
     *
     * Verifica que exista una sesión activa antes de mostrar la vista.
     *
     * @return void
     */
    public function home(): void
    {
        session_start();
        if (!isset($_SESSION['admin'])) {
            header("Location: " . Enlaces::BASE_URL . "admin/login_admin");
            exit;
        }

        require Enlaces::VIEW_PATH . "admin/home_admin.php";
    }

    /*************************************************** CERRAR SESIÓN *********************************************************/

    /**
     * Cierra la sesión del administrador
     *
     * Elimina los datos de sesión y redirige al login.
     *
     * @return void
     */
    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: " . Enlaces::BASE_URL . "admin/login_admin");
        exit;
    }
}
