<?php

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Clinica;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Administrador;

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

    public function home_medicos()
    {
        require Enlaces::VIEW_CONTENT_CLINICA_PATH . "medicos.php";
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

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "clinica/loguear_clinica");
            exit;
        }

        $pdo = BaseDatos::getConexion();
        $adminModel = new Administrador();
        $id_admin = $idAdmin ?? null;
        $userAdmin = $adminModel->mostrarAdmin($pdo, $id_admin);


        // Sanitizar entrada
        $nombre     = trim(filter_input(INPUT_POST, 'nombre_clinica', FILTER_SANITIZE_STRING));
        $direccion  = trim(filter_input(INPUT_POST, 'direccion_clinica', FILTER_SANITIZE_STRING));
        $telefono   = trim(filter_input(INPUT_POST, 'telefono_clinica', FILTER_SANITIZE_STRING));
        $email      = trim(filter_input(INPUT_POST, 'email_clinica', FILTER_SANITIZE_EMAIL));
        $usuario    = trim(filter_input(INPUT_POST, 'usuario_clinica', FILTER_SANITIZE_STRING));
        $fotoRuta = trim($_FILES['foto_clinica']['name']);
        $pass1      = trim($_POST['password_clinica'] ?? '');
        $pass2      = trim($_POST['password2_clinica'] ?? '');

        // Validar contraseñas
        if ($pass1 !== $pass2) {
            die("Las contraseñas no coinciden.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
        }

        // Control de la ruta de la foto introducida
        if (!empty($_FILES['foto_clinica']['name'])) {

            // RUTA FÍSICA REAL
            $directorioFisico = Enlaces::BASE_PATH . 'app/imagenes_registros/imagenes_clinicas/';

            if (!is_dir($directorioFisico)) {
                mkdir($directorioFisico, 0777, true);
            }

            $extension = strtolower(pathinfo($_FILES['foto_clinica']['name'], PATHINFO_EXTENSION));

            $extPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($extension, $extPermitidas)) {
                die('Formato de imagen no permitido');
            }

            $nombreArchivo = 'clinica_' . time() . '.' . $extension;
            $rutaFisicaFinal = $directorioFisico . $nombreArchivo;

            if (move_uploaded_file($_FILES['foto_clinica']['tmp_name'], $rutaFisicaFinal)) {

                //SOLO guardamos la RUTA RELATIVA para la BD
                $fotoRuta = $nombreArchivo;
            }
        }

        // Imagen por defecto
        if (!$fotoRuta) {
            $fotoRuta = 'imagen_clinica_por_defecto.png';
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
        $clinica->setUsuarioAdminClinica($userAdmin['usuario_admin']);
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
            'usuario_admin_clinica'    => $resultado['usuario_admin_clinica'],
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

    /* =======================
       MOSTRAR FORMULARIO
    ======================= */
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $id_clinica = filter_input(INPUT_GET, 'id_clinica', FILTER_VALIDATE_INT);
        if (!$id_clinica) {
            header("Location: " . Enlaces::BASE_URL . "admin/home/clinicas");
            exit;
        }

        $clinica = $clinicaModel->mostrarClinicaPorId($pdo, $id_clinica);

        if (!$clinica) {
            die("Clínica no encontrada");
        }

        if ((int)$clinica['id_admin'] !== (int)$_SESSION['admin']['id_admin']) {
            die("No tienes permisos para modificar esta clínica");
        }

        // Cargar la vista de edición
        require Enlaces::VIEW_PATH . "clinica/editar_clinica.php";
        exit;
    }

    /* =======================
       GUARDAR CAMBIOS
    ======================= */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id_clinica = filter_input(INPUT_POST, 'id_clinica', FILTER_VALIDATE_INT);
        if (!$id_clinica) {
            die("ID inválido");
        }

        $clinica = $clinicaModel->mostrarClinicaPorId($pdo, $id_clinica);

        if (!$clinica) {
            die("Clínica no encontrada");
        }

        if ((int)$clinica['id_admin'] !== (int)$_SESSION['admin']['id_admin']) {
            die("No tienes permisos");
        }

        // Datos
        $nombre    = trim($_POST['nombre_clinica']);
        $direccion = trim($_POST['direccion_clinica']);
        $telefono  = trim($_POST['telefono_clinica']);
        $email     = trim($_POST['email_clinica']);
        $usuario   = trim($_POST['usuario_clinica']);

        // LOGO
        $logo = $clinica['foto_clinica']; // Mantener logo actual

        if (!empty($_FILES['foto_clinica']['name'])) {
            $logo = uniqid() . '_' . $_FILES['foto_clinica']['name'];
            move_uploaded_file(
                $_FILES['foto_clinica']['tmp_name'],
                Enlaces::BASE_PATH . "app/imagenes_registros/imagenes_clinicas/" . $logo
            );
        }

        // Setear modelo
        $clinicaModel->setNombreClinica($nombre);
        $clinicaModel->setDireccionClinica($direccion);
        $clinicaModel->setTelefonoClinica($telefono);
        $clinicaModel->setEmailClinica($email);
        $clinicaModel->setUsuarioClinica($usuario);
        $clinicaModel->setFotoClinica($logo);

        if (!$clinicaModel->actualizarClinica($pdo, $id_clinica)) {
            die("Error al actualizar la clínica");
        }

        header("Location: " . Enlaces::BASE_URL . "admin/home/clinicas");
        exit;
    }
}
}
