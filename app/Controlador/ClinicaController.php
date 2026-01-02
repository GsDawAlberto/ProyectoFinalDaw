<?php

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Clinica;
use Mediagend\App\Config\BaseDatos;

class ClinicaController
{
    /**********************   RUTA DE VISTAS ***********************************/

    public function home_citas()
    {
        require Enlaces::VIEW_CONTENT_ADMIN_PATH . "citas.php";
    }

    public function home_pacientes()
    {
        require Enlaces::VIEW_CONTENT_CLINICA_PATH . "pacientes.php";
    }
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
        $userAdmin = 'Alberto';

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
        $usuarioAs   = trim(filter_input(INPUT_POST, 'usuario_admin', FILTER_SANITIZE_STRING));
        $fotoRuta = trim($_FILES['foto_clinica']['name']);
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
        $clinica->setUsuarioAdmin($usuarioAs);
        $clinica->setFotoClinica($fotoRuta);


        // Guardar
        $guardado = $clinica->guardarClinica($pdo);

        if (!$guardado) {
            die("Error al registrar la clínica.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
        }

        // Redirigir a home admin
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
        $usuario = trim(filter_input(INPUT_POST, 'usuario_clinica', FILTER_SANITIZE_STRING) ?? '');
        $password = trim(filter_input(INPUT_POST, 'password_clinica', FILTER_SANITIZE_STRING) ?? '');

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
            'id_clinica'       => $resultado['id_clinica'],
            'nombre_clinica'   => $resultado['nombre_clinica'],
            'usuario_clinica'  => $resultado['usuario_clinica'],
            'usuario_admin'    => $resultado['usuario_admin'],
            'email_clinica'    => $resultado['email_clinica'],
            'foto_clinica'     => $resultado['foto_clinica']
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

    /************************* ELIMINAR CLINICA *************************/
    public function eliminar()
    {
        session_start();

        // Verificar sesión admin
        if (!isset($_SESSION['admin'])) {
            header("Location: " . Enlaces::BASE_URL . "admin/login_admin");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . Enlaces::BASE_URL . "admin/home/clinicas");
            exit;
        }

        $id_clinica = filter_input(INPUT_POST, 'id_clinica', FILTER_VALIDATE_INT);
        if (!$id_clinica) {
            die("ID de clínica inválido");
        }

        $pdo = BaseDatos::getConexion();
        $clinicaModel = new Clinica();

        // Obtener clínica para comprobar propietario
        $clinica = $clinicaModel->mostrarClinica($pdo, $id_clinica);

        if (!$clinica) {
            die("La clínica no existe");
        }

        // Seguridad: solo el admin creador puede borrar
        if ((int)$clinica['id_admin'] !== (int)$_SESSION['admin']['id_admin']) {
            die("No tienes permisos para eliminar esta clínica");
        }

        // Eliminar
        if (!$clinicaModel->eliminarClinica($pdo, $id_clinica)) {
            die("Error al eliminar la clínica");
        }

        header("Location: " . Enlaces::BASE_URL . "admin/home/clinicas");
        exit;
    }


    /************************* MODIFICAR CLINICA *************************/
    public function modificar()
    {
        session_start();

        if (!isset($_SESSION['admin'])) {
            header("Location: " . Enlaces::BASE_URL . "admin/login_admin");
            exit;
        }

        $pdo = BaseDatos::getConexion();
        $clinicaModel = new Clinica();

        // =======================
        // MOSTRAR FORMULARIO
        // =======================
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $id_clinica = filter_input(INPUT_GET, 'id_clinica', FILTER_VALIDATE_INT);
            if (!$id_clinica) {
                header("Location: " . Enlaces::BASE_URL . "admin/home/clinicas");
                exit;
            }

            $clinica = $clinicaModel->mostrarClinica($pdo, $id_clinica);

            if (!$clinica) {
                die("Clínica no encontrada");
            }

            // Seguridad
            if ((int)$clinica['id_admin'] !== (int)$_SESSION['admin']['id_admin']) {
                die("No tienes permisos para modificar esta clínica");
            }

            // Pasar $clinica a la vista
            require Enlaces::VIEW_PATH . "admin/clinica/editar_clinica.php";
            exit;
        }

        // =======================
        // GUARDAR CAMBIOS
        // =======================
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id_clinica = filter_input(INPUT_POST, 'id_clinica', FILTER_VALIDATE_INT);

            if (!$id_clinica) {
                die("ID inválido");
            }

            $clinica = $clinicaModel->mostrarClinica($pdo, $id_clinica);

            if (!$clinica) {
                die("Clínica no encontrada");
            }

            if ((int)$clinica['id_admin'] !== (int)$_SESSION['admin']['id_admin']) {
                die("No tienes permisos");
            }

            // Sanitizar datos
            $nombre    = trim(filter_input(INPUT_POST, 'nombre_clinica', FILTER_SANITIZE_STRING));
            $direccion = trim(filter_input(INPUT_POST, 'direccion_clinica', FILTER_SANITIZE_STRING));
            $telefono  = trim(filter_input(INPUT_POST, 'telefono_clinica', FILTER_SANITIZE_STRING));
            $email     = trim(filter_input(INPUT_POST, 'email_clinica', FILTER_SANITIZE_EMAIL));
            $usuario   = trim(filter_input(INPUT_POST, 'usuario_clinica', FILTER_SANITIZE_STRING));

            // Setear modelo
            $clinicaModel->setIdClinica($id_clinica);
            $clinicaModel->setNombreClinica($nombre);
            $clinicaModel->setDireccionClinica($direccion);
            $clinicaModel->setTelefonoClinica($telefono);
            $clinicaModel->setEmailClinica($email);
            $clinicaModel->setUsuarioClinica($usuario);

            if (!$clinicaModel->actualizarClinica($pdo, $id_clinica)) {
                die("Error al actualizar la clínica");
            }

            header("Location: " . Enlaces::BASE_URL . "admin/home/clinicas");
            exit;
        }
    }
}
