<?php

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Clinica;
use Mediagend\App\Config\BaseDatos;

/**
 * Controlador de Clínicas
 *
 * Gestiona vistas, registro, login, modificación, eliminación
 * y sesión de clínicas en la aplicación.
 *
 * @package Mediagend\App\Controlador
 */
class ClinicaController
{
    /**********************   RUTA DE VISTAS ***********************************/

    /**
     * Muestra la vista de gestión de citas
     *
     * @return void
     */
    public function home_citas()
    {
        require Enlaces::VIEW_CONTENT_ADMIN_PATH . "citas.php";
    }
    /**
     * Muestra la vista de gestión de pacientes
     *
     * @return void
     */
    public function home_pacientes()
    {
        require Enlaces::VIEW_CONTENT_CLINICA_PATH . "pacientes.php";
    }
    /**
     * Muestra la vista de gestión de médicos
     *
     * @return void
     */
    public function home_medicos()
    {
        require Enlaces::VIEW_CONTENT_CLINICA_PATH . "medicos.php";
    }
    /********************* FORMULARIO LOGIN ***********************/
    /**
     * Muestra la vista de login de la clínica
     *
     * @return void
     */
    public function login()
    {
        require Enlaces::VIEW_PATH . "clinica/login_clinica.php";
    }

    /********************* FORMULARIO REGISTRO *********************/
    /**
     * Muestra la vista de registro de la clínica
     *
     * @return void
     */
    public function loguear()
    {
        require Enlaces::VIEW_PATH . "clinica/loguear_clinica.php";
    }

    /********************* PROCESAR REGISTRO *********************/
    /**
     * Procesa el registro de una nueva clínica
     *
     * Valida datos, maneja la subida de imagen y guarda la clínica en la base de datos.
     *
     * @return void
     */
    public function registrar()
    {
        session_start();

        if (!isset($_SESSION['admin'])) {
            header("Location: " . Enlaces::BASE_URL . "admin/login_admin");
            exit;
        }

        $idAdmin = $_SESSION['admin']['id_admin'];
        $usuarioAdministrador = $_SESSION['admin']['usuario_admin'];

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "clinica/loguear_clinica");
            exit;
        }


        //   SANITIZAR ENTRADAS

        $nombre     = trim(filter_input(INPUT_POST, 'nombre_clinica', FILTER_SANITIZE_SPECIAL_CHARS));
        $nif        = strtoupper(trim(filter_input(INPUT_POST, 'nif_clinica', FILTER_SANITIZE_SPECIAL_CHARS)));
        $direccion  = trim(filter_input(INPUT_POST, 'direccion_clinica', FILTER_SANITIZE_SPECIAL_CHARS));
        $telefono   = trim(filter_input(INPUT_POST, 'telefono_clinica', FILTER_SANITIZE_SPECIAL_CHARS));
        $email      = trim(filter_input(INPUT_POST, 'email_clinica', FILTER_SANITIZE_EMAIL));
        $usuario    = trim(filter_input(INPUT_POST, 'usuario_clinica', FILTER_SANITIZE_SPECIAL_CHARS));
        $fotoRuta   = trim($_FILES['foto_clinica']['name'] ?? '');
        $pass1      = trim($_POST['password'] ?? '');
        $pass2      = trim($_POST['password_2'] ?? '');


        //   VALIDACIONES

        if (strlen($nombre) < 3 || strlen($nombre) > 30) {
            die("El nombre debe tener entre 3 y 30 caracteres.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
        }

        // NIF → DNI o CIF
        $dniRegex = '/^[0-9]{8}[A-Z]$/';
        $cifRegex = '/^[A-Z][0-9]{8}$/';

        if (!preg_match($dniRegex, $nif) && !preg_match($cifRegex, $nif)) {
            die("El NIF debe ser un DNI o CIF válido.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
        }

        if (strlen($direccion) < 5 || strlen($direccion) > 100) {
            die("La dirección debe tener entre 5 y 100 caracteres.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
        }

        if (!preg_match('/^[0-9]{9}$/', $telefono)) {
            die("El teléfono debe contener 9 dígitos numéricos.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("El email no tiene un formato válido.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
        }

        if (strlen($usuario) < 3 || strlen($usuario) > 15) {
            die("El usuario debe tener entre 3 y 15 caracteres.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
        }

        if ($pass1 !== $pass2 || strlen($pass1) < 6) {
            die("Las contraseñas deben coincidir y tener al menos 6 caracteres.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
        }


        //  GESTIÓN DE IMAGEN
        $fotoRuta = null;

        if (!empty($_FILES['foto_clinica']['name'])) {

            $directorioFisico = Enlaces::BASE_PATH . 'app/imagenes_registros/imagenes_clinicas/';

            if (!is_dir($directorioFisico)) {
                mkdir($directorioFisico, 0777, true);
            }

            $extension = strtolower(pathinfo($_FILES['foto_clinica']['name'], PATHINFO_EXTENSION));
            $extPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extension, $extPermitidas)) {
                die("Formato de imagen no permitido.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
            }

            $nombreArchivo = 'clinica_' . time() . '.' . $extension;
            $rutaFisicaFinal = $directorioFisico . $nombreArchivo;

            if (move_uploaded_file($_FILES['foto_clinica']['tmp_name'], $rutaFisicaFinal)) {
                $fotoRuta = $nombreArchivo;
            }
        }

        if (!$fotoRuta) {
            $fotoRuta = 'imagen_clinica_por_defecto.png';
        }


        //   BD + MODELO

        $pdo = BaseDatos::getConexion();

        $clinica = new Clinica();
        $clinica->setIdAdmin($idAdmin);
        $clinica->setNombreClinica($nombre);
        $clinica->setNifClinica($nif);
        $clinica->setDireccionClinica($direccion);
        $clinica->setTelefonoClinica($telefono);
        $clinica->setEmailClinica($email);
        $clinica->setUsuarioClinica($usuario);
        $clinica->setPasswordClinica($pass1);
        $clinica->setUsuarioAdminClinica($usuarioAdministrador);
        $clinica->setFotoClinica($fotoRuta);

        $guardado = $clinica->guardarClinica($pdo);

        if (!$guardado) {
            die("Error al registrar la clínica.<br><a href='" . Enlaces::BASE_URL . "clinica/loguear_clinica'>Volver</a>");
        }

        header("Location: " . Enlaces::BASE_URL . "admin/home/clinicas");
        exit;
    }

    /***************************** PROCESAR LOGIN  *************************************/
    /**
     * Procesa el login de la clínica
     *
     * @return void
     */
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

        // Validaciones

        if (strlen($usuario) < 3 || strlen($usuario) > 15) {
            die("El usuario debe tener entre 3 y 15 caracteres.<br><a href='" . Enlaces::BASE_URL . "clinica/login_clinica'>Volver</a>");
        }

        if (strlen($password) < 6) {
            die("La contraseña debe tener al menos 6 caracteres.<br><a href='" . Enlaces::BASE_URL . "clinica/login_clinica'>Volver</a>");
        }

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
            'nif_clinica'      => $resultado['nif_clinica'],
            'usuario_clinica'  => $resultado['usuario_clinica'],
            'usuario_admin_clinica'    => $resultado['usuario_admin_clinica'],
            'email_clinica'    => $resultado['email_clinica'],
            'foto_clinica'     => $resultado['foto_clinica']
        ];

        header("Location: " . Enlaces::BASE_URL . "clinica/home_clinica");
        exit;
    }

    /*************************  HOME CLINICA *************************/
    /**
     * Muestra la vista principal de la clínica
     *
     * @return void
     */
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
    /**
     * Cierra la sesión de la clínica
     *
     * @return void
     */
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header("Location: " . Enlaces::BASE_URL . "clinica/login_clinica");
        exit;
    }

    /************************* ELIMINAR CLINICA *************************/
    /**
     * Elimina una clínica
     *
     * Solo el admin creador puede eliminar la clínica.
     *
     * @return void
     */
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
    /**
     * Modifica los datos de una clínica
     *
     * Soporta mostrar formulario por GET y guardar cambios por POST.
     *
     * @return void
     */
    public function modificar()
    {
        session_start();

        if (!isset($_SESSION['admin'])) {
            header("Location: " . Enlaces::BASE_URL . "admin/login_admin");
            exit;
        }

        $pdo = BaseDatos::getConexion();
        $clinicaModel = new Clinica();


        //   MOSTRAR FORMULARIO POR GET PARA PODER VER QUE ENTRADAS TIENE 

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

            require Enlaces::VIEW_PATH . "clinica/editar_clinica.php";
            exit;
        }


        //   GUARDAR CAMBIOS

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


            //  DATOS FORMULARIO

            $nombre    = trim(filter_input(INPUT_POST, 'nombre_clinica', FILTER_SANITIZE_SPECIAL_CHARS));
            $nif       = strtoupper(trim(filter_input(INPUT_POST, 'nif_clinica', FILTER_SANITIZE_SPECIAL_CHARS)));
            $direccion = trim(filter_input(INPUT_POST, 'direccion_clinica', FILTER_SANITIZE_SPECIAL_CHARS));
            $telefono  = trim(filter_input(INPUT_POST, 'telefono_clinica', FILTER_SANITIZE_SPECIAL_CHARS));
            $email     = trim(filter_input(INPUT_POST, 'email_clinica', FILTER_SANITIZE_EMAIL));
            $usuario   = trim(filter_input(INPUT_POST, 'usuario_clinica', FILTER_SANITIZE_SPECIAL_CHARS));
            $usuarioAdmin = trim($_POST['usuario_admin_clinica'] ?? $clinica['usuario_admin_clinica']);


            //   VALIDACIONES

            if (strlen($nombre) < 3 || strlen($nombre) > 30) {
                die("El nombre debe tener entre 3 y 30 caracteres.<br><a href='" . Enlaces::BASE_URL . "admin/home/clinicas'>Volver</a>");
            }

            // NIF = DNI o CIF
            $dniRegex = '/^[0-9]{8}[A-Z]$/'; // PATTER PARA DNI
            $cifRegex = '/^[A-Z][0-9]{8}$/'; // PATTER PARA CIF

            if (!preg_match($dniRegex, $nif) && !preg_match($cifRegex, $nif)) {
                die("El NIF debe ser un DNI o CIF válido.<br><a href='" . Enlaces::BASE_URL . "admin/home/clinicas'>Volver</a>");
            }

            if (strlen($direccion) < 5 || strlen($direccion) > 100) {
                die("La dirección debe tener entre 5 y 100 caracteres.<br><a href='" . Enlaces::BASE_URL . "admin/home/clinicas'>Volver</a>");
            }

            if (!preg_match('/^[0-9]{9}$/', $telefono)) {
                die("El teléfono debe contener 9 dígitos numéricos.<br><a href='" . Enlaces::BASE_URL . "admin/home/clinicas'>Volver</a>");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                die("El email no tiene un formato válido.<br><a href='" . Enlaces::BASE_URL . "admin/home/clinicas'>Volver</a>");
            }

            if (strlen($usuario) < 3 || strlen($usuario) > 15) {
                die("El usuario debe tener entre 3 y 15 caracteres.<br><a href='" . Enlaces::BASE_URL . "admin/home/clinicas'>Volver</a>");
            }


            // LOGO
            $logo = $clinica['foto_clinica']; // mantener logo actual

            if (!empty($_FILES['foto_clinica']['name'])) {

                $directorioFisico = Enlaces::BASE_PATH . 'app/imagenes_registros/imagenes_clinicas/';

                if (!is_dir($directorioFisico)) {
                    mkdir($directorioFisico, 0777, true);
                }

                $extension = strtolower(pathinfo($_FILES['foto_clinica']['name'], PATHINFO_EXTENSION));
                $extPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array($extension, $extPermitidas)) {
                    die("Formato de imagen no permitido.<br>
        <a href='" . Enlaces::BASE_URL . "clinica/modificar?id_clinica={$id_clinica}'>Volver</a>");
                }

                $nombreArchivo = 'clinica_' . uniqid() . '.' . $extension;
                $rutaFinal = $directorioFisico . $nombreArchivo;

                if (move_uploaded_file($_FILES['foto_clinica']['tmp_name'], $rutaFinal)) {

                    // borrar logo anterior
                    if (!empty($clinica['foto_clinica'])) {
                        $logoAnterior = $directorioFisico . $clinica['foto_clinica'];
                        if (file_exists($logoAnterior)) {
                            unlink($logoAnterior);
                        }
                    }

                    $logo = $nombreArchivo;
                }
            }


            //   ENTRADA DE DATOS AL MODELO 
            $clinicaModel->setNombreClinica($nombre);
            $clinicaModel->setNifClinica($nif);
            $clinicaModel->setDireccionClinica($direccion);
            $clinicaModel->setTelefonoClinica($telefono);
            $clinicaModel->setEmailClinica($email);
            $clinicaModel->setUsuarioClinica($usuario);
            $clinicaModel->setFotoClinica($logo);
            $clinicaModel->setUsuarioAdminClinica($usuarioAdmin);

            if (!$clinicaModel->actualizarClinica($pdo, $id_clinica)) {
                die("Error al actualizar la clínica.<br><a href='" . Enlaces::BASE_URL . "admin/home/clinicas'>Volver</a>");
            }

            header("Location: " . Enlaces::BASE_URL . "admin/home/clinicas");
            exit;
        }
    }
}
