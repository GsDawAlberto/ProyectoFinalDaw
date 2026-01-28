<?php

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Medico;
use Mediagend\App\Config\BaseDatos;

/**
 * Controlador de Médicos
 *
 * Gestiona todas las acciones relacionadas con los médicos,
 * incluyendo registro, login, modificación, eliminación y vistas de home.
 *
 * @package Mediagend\App\Controlador
 */
class MedicoController
{
    /**************************** RUTA DE VISTAS **************************/
    /**
     * Controlador de Médicos
     *
     * Gestiona todas las acciones relacionadas con los médicos,
     * incluyendo registro, login, modificación, eliminación y vistas de home.
     *
     * @package Mediagend\App\Controlador
     */
    public function home_mis_pacientes()
    {

        require Enlaces::VIEW_PATH . "medico/contenido_medico_home/mis_pacientes.php";
    }

    /**************************** FORMULARIO LOGIN *************************/
    /**
     * Muestra el formulario de login para médicos
     *
     * @return void
     */
    public function login()
    {
        require Enlaces::VIEW_PATH . "medico/login_medico.php";
    }

    /**************************** FORMULARIO REGISTRO *************************/
    /**
     * Muestra el formulario de registro de médicos
     *
     * @return void
     */
    public function loguear()
    {
        require Enlaces::VIEW_PATH . "medico/loguear_medico.php";
    }

    /**************************** PROCESAR REGISTRO *************************/
    /**
     * Procesa el registro de un nuevo médico
     *
     * Valida campos, gestiona subida de imagen, guarda el médico en BD
     * y redirige al home de la clínica.
     *
     * @return void
     */
    public function registrar()
    {
        session_start();

        if (!isset($_SESSION['clinica'])) {
            header("Location: " . Enlaces::BASE_URL . "clinica/login_clinica");
            exit;
        }

        $idClinica = $_SESSION['clinica']['id_clinica'];

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "medico/loguear_medico");
            exit;
        }

        $fotoRuta = null;

        // Sanitizar entrada
        $nombre                 = trim(filter_input(INPUT_POST, 'nombre_medico', FILTER_SANITIZE_STRING));
        $apellidos              = trim(filter_input(INPUT_POST, 'apellidos_medico', FILTER_SANITIZE_STRING));
        $dni                    = strtoupper(trim(filter_input(INPUT_POST, 'dni_medico', FILTER_SANITIZE_STRING)));
        $numero_colegiado       = trim(filter_input(INPUT_POST, 'numero_colegiado', FILTER_SANITIZE_STRING));
        $especialidad_medico    = trim(filter_input(INPUT_POST, 'especialidad_medico', FILTER_SANITIZE_EMAIL));
        $telefono               = trim(filter_input(INPUT_POST, 'telefono_medico', FILTER_SANITIZE_STRING));
        $email                  = trim(filter_input(INPUT_POST, 'email_medico', FILTER_SANITIZE_EMAIL));
        $fotoRuta               = trim($_FILES['foto_medico']['name']);
        $pass1                  = trim($_POST['password'] ?? '');
        $pass2                  = trim($_POST['password_2'] ?? '');

        // Validaciones
        if (
            !$numero_colegiado || !$especialidad_medico || !$nombre ||
            !$apellidos || !$dni || !$telefono || !$email || !$pass1 || !$pass2
        ) {
            die("Todos los campos son obligatorios.<br>
         <a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }

        // Número de colegiado
        if (!preg_match('/^[0-9]{9}$/', $numero_colegiado)) {
            die("El número de colegiado debe tener 9 dígitos.<br>
         <a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }

        // Especialidad
        if (strlen($especialidad_medico) < 3 || strlen($especialidad_medico) > 50) {
            die("Especialidad inválida.<br>
         <a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }

        // Nombre
        if (strlen($nombre) < 3 || strlen($nombre) > 30) {
            die("Nombre inválido.<br>
         <a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }

        // Apellidos
        if (strlen($apellidos) < 3 || strlen($apellidos) > 50) {
            die("Apellidos inválidos.<br>
         <a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }

        // DNI formato
        if (!preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
            die("Formato de DNI inválido.<br>
         <a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }

        // DNI letra
        $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        if ($letras[intval(substr($dni, 0, 8)) % 23] !== $dni[8]) {
            die("Letra del DNI incorrecta.<br>
         <a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }

        // Teléfono
        if (!preg_match('/^[0-9]{9}$/', $telefono)) {
            die("El teléfono debe tener 9 dígitos.<br>
         <a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }

        // Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Email inválido.<br>
         <a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }

        // Password longitud
        if (strlen($pass1) < 6) {
            die("La contraseña debe tener al menos 6 caracteres.<br>
         <a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }

        // Passwords iguales
        if ($pass1 !== $pass2) {
            die("Las contraseñas no coinciden.<br>
         <a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }


        // Control de la ruta de la foto introducida
        $fotoRuta = null;
        if (!empty($_FILES['foto_medico']['name'])) {

            // RUTA FÍSICA REAL
            $directorioFisico = Enlaces::BASE_PATH . 'app/imagenes_registros/imagenes_medicos/';

            if (!is_dir($directorioFisico)) {
                mkdir($directorioFisico, 0777, true);
            }

            $extension = strtolower(pathinfo($_FILES['foto_medico']['name'], PATHINFO_EXTENSION));

            $extPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($extension, $extPermitidas)) {
                die('Formato de imagen no permitido');
            }

            $nombreArchivo = 'medico_' . time() . '.' . $extension;
            $rutaFisicaFinal = $directorioFisico . $nombreArchivo;

            if (move_uploaded_file($_FILES['foto_medico']['tmp_name'], $rutaFisicaFinal)) {

                //SOLO guardamos la RUTA RELATIVA para la BD
                $fotoRuta = $nombreArchivo;
            }
        }

        // Imagen por defecto
        if (!$fotoRuta) {
            $fotoRuta = 'imagen_medico_por_defecto.jpg';
        }

        // Conexión BD
        $pdo = BaseDatos::getConexion();

        // Crear modelo
        $medico = new Medico();
        $medico->setIdClinica($idClinica);
        $medico->setNombreMedico($nombre);
        $medico->setApellidosMedico($apellidos);
        $medico->setDniMedico($dni);
        $medico->setNumeroColegiado($numero_colegiado);
        $medico->setEspecialidadMedico($especialidad_medico);
        $medico->setTelefonoMedico($telefono);
        $medico->setEmailMedico($email);
        $medico->setFotoMedico($fotoRuta);
        $medico->setPasswordMedico($pass1);


        //Guardar
        $guardado = $medico->guardarMedico($pdo);

        if (!$guardado) {
            die("Error al registrar el medico.<br><a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }

        // Redirigir a home de clinica
        header("Location: " . Enlaces::BASE_URL . "clinica/home/pacientes");
        exit;
    }

    /*********************************** PROCESAR LOGIN *************************************/
    /**
     * Procesa el login de un médico
     *
     * Valida número de colegiado y contraseña, inicia sesión y redirige
     * al home del médico.
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
        //Sanitizar entrada
        $colegiado = trim(filter_input(INPUT_POST, 'numero_colegiado', FILTER_SANITIZE_STRING) ?? '');
        $password = trim(filter_input(INPUT_POST, 'password_medico', FILTER_SANITIZE_STRING) ?? '');

        // Validaciones

        // Campos obligatorios
        if (!$colegiado || !$password) {
            die("Todos los campos son obligatorios.<br>
         <a href='" . Enlaces::BASE_URL . "medico/login_medico'>Volver</a>");
        }

        // Número de colegiado
        if (!preg_match('/^[0-9]{9}$/', $colegiado)) {
            die("El número de colegiado debe tener 9 dígitos numéricos.<br>
         <a href='" . Enlaces::BASE_URL . "medico/login_medico'>Volver</a>");
        }

        // Contraseña
        if (strlen($password) < 6) {
            die("La contraseña debe tener al menos 6 caracteres.<br>
         <a href='" . Enlaces::BASE_URL . "medico/login_medico'>Volver</a>");
        }


        //Conexión BD
        $pdo = BaseDatos::getConexion();

        //Autenticar
        $medico = new Medico();
        $resultado = $medico->autenticarMedico($pdo, $colegiado, $password);

        if (!$resultado) {
            echo "Error al autenticar el medico.<br> ";
            echo "<a href='" . Enlaces::BASE_URL . "medico/login_medico'>Volver</a>";
            exit;
        }

        session_start();

        //Guardar sesión
        $_SESSION['medico'] = [
            'id_medico'             => $resultado['id_medico'],
            'id_clinica'            => $resultado['id_clinica'],
            'nombre_medico'         => $resultado['nombre_medico'],
            'apellidos_medico'      => $resultado['apellidos_medico'],
            'dni_medico'            => $resultado['dni_medico'],
            'numero_colegiado'      => $resultado['numero_colegiado'],
            'telefono_medico'       => $resultado['telefono_medico'],
            'email_medico'          => $resultado['email_medico'],
            'especialidad_medico'   => $resultado['especialidad_medico'],
            'foto_medico'           => $resultado['foto_medico']
        ];

        header("Location: " . Enlaces::BASE_URL . "medico/home_medico");
        exit;
    }


    /*************************  HOME MEDICO *************************/
    /**
     * Muestra la vista principal del médico
     *
     * @return void
     */
    public function home()
    {
        session_start();

        if (!isset($_SESSION['medico'])) {
            header("Location: " . Enlaces::BASE_URL . "medico/login_medico");
            exit;
        }

        require Enlaces::VIEW_PATH . "medico/home_medico.php";
    }

    /*************************  CERRAR SESIÓN *************************/
    /**
     * Cierra la sesión del médico
     *
     * @return void
     */
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header("Location: " . Enlaces::BASE_URL . "medico/login_medico");
        exit;
    }

    /******************************* ELIMINAR MEDICO ***********************************/
    /**
     * Elimina un médico de la clínica
     *
     * Comprueba permisos, valida ID y elimina tanto la imagen
     * como el registro en la base de datos.
     *
     * @return void
     */
    public function eliminar()
    {
        session_start();
        //Verificar sesión clinica
        if (!isset($_SESSION['clinica'])) {
            header("Location: " . Enlaces::BASE_URL . "clinica/login_clinica");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . Enlaces::BASE_URL . "clinica/home/medicos");
            exit;
        }

        $id_medico = filter_input(INPUT_POST, 'id_medico', FILTER_VALIDATE_INT);
        if (!$id_medico) {
            die("ID del médico inválido");
        }

        $pdo = BaseDatos::getConexion();
        $medicoModel = new Medico();

        //Obtener paciente para comprobar propietario
        $medico = $medicoModel->mostrarMedicoPorId($pdo, $id_medico);

        if ($medico === 'ERR_MEDICO_03') {
            die("Error al obtener el médico");
        }

        if (!$medico || !is_array($medico)) {
            die("El médico no existe");
        }

        //Seguridad: solo la clinica creadora puede borrar
        if ((int)$medico['id_clinica'] !== (int)$_SESSION['clinica']['id_clinica']) {
            die("No tienes permisos para eliminar este medico");
        }

        //Eliminar
        if (!$medicoModel->eliminarMedico($pdo, $id_medico)) {
            die("Error al eliminar el médico");
        }

        header("Location: " . Enlaces::BASE_URL . "clinica/home/medicos");
        exit;
    }

    /************************* MODIFICAR MÉDICO *************************/
    /**
     * Modifica los datos de un médico
     *
     * Muestra el formulario por GET y procesa cambios por POST,
     * incluyendo validaciones y subida de foto.
     *
     * @return void
     */
    public function modificar()
    {
        session_start();

        // Verificar sesión clínica
        if (!isset($_SESSION['clinica'])) {
            header("Location: " . Enlaces::BASE_URL . "clinica/login_clinica");
            exit;
        }

        $pdo = BaseDatos::getConexion();
        $medicoModel = new Medico();


        //MOSTRAR FORMULARIO CON GET

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $id_medico = filter_input(INPUT_GET, 'id_medico', FILTER_VALIDATE_INT);
            if (!$id_medico) {
                header("Location: " . Enlaces::BASE_URL . "clinica/home/medicos");
                exit;
            }

            $medico = $medicoModel->mostrarMedicoPorId($pdo, $id_medico);

            if (!$medico) {
                die("Médico no encontrado");
            }

            // Seguridad: solo la clínica propietaria
            if ((int)$medico['id_clinica'] !== (int)$_SESSION['clinica']['id_clinica']) {
                die("No tienes permisos para modificar este médico");
            }

            require Enlaces::VIEW_PATH . "medico/editar_medico.php";
            exit;
        }


        // GUARDAR CAMBIOS Y ENVIARLOS POR POST A SU RUTA
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id_medico = filter_input(INPUT_POST, 'id_medico', FILTER_VALIDATE_INT);
            if (!$id_medico) {
                die("ID inválido");
            }

            $medico = $medicoModel->mostrarMedicoPorId($pdo, $id_medico);

            if (!$medico) {
                die("Médico no encontrado");
            }

            if ((int)$medico['id_clinica'] !== (int)$_SESSION['clinica']['id_clinica']) {
                die("No tienes permisos");
            }

            // ENTRADA DE DATOS MEDIANTE POST
            $nombre              = trim($_POST['nombre_medico']);
            $apellidos           = trim($_POST['apellidos_medico']);
            $dni                 = strtoupper(trim($_POST['dni_medico']));
            $numero_colegiado    = trim($_POST['numero_colegiado']);
            $especialidad        = trim($_POST['especialidad_medico']);
            $telefono            = trim($_POST['telefono_medico']);
            $email               = trim($_POST['email_medico']);

            // Validaciones

            // Campos obligatorios
            if (!$nombre || !$apellidos || !$numero_colegiado || !$dni) {
                die("Todos los campos obligatorios deben estar completos.<br>
         <a href='" . Enlaces::BASE_URL . "clinica/home/medicos'>Volver</a>");
            }

            // Nombre
            if (strlen($nombre) < 3 || strlen($nombre) > 30) {
                die("Nombre inválido.<br>
         <a href='" . Enlaces::BASE_URL . "clinica/home/medicos'>Volver</a>");
            }

            // Apellidos
            if (strlen($apellidos) < 3 || strlen($apellidos) > 50) {
                die("Apellidos inválidos.<br>
         <a href='" . Enlaces::BASE_URL . "clinica/home/medicos'>Volver</a>");
            }

            // DNI formato
            if (!preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
                die("Formato de DNI inválido.<br>
         <a href='" . Enlaces::BASE_URL . "clinica/home/medicos'>Volver</a>");
            }

            // DNI letra
            $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
            if ($letras[intval(substr($dni, 0, 8)) % 23] !== $dni[8]) {
                die("Letra del DNI incorrecta.<br>
         <a href='" . Enlaces::BASE_URL . "clinica/home/medicos'>Volver</a>");
            }

            // Número de colegiado
            if (!preg_match('/^[0-9]{9}$/', $numero_colegiado)) {
                die("El número de colegiado debe tener 9 dígitos.<br>
         <a href='" . Enlaces::BASE_URL . "clinica/home/medicos'>Volver</a>");
            }

            // Especialidad
            if ($especialidad !== '' && (strlen($especialidad) < 3 || strlen($especialidad) > 50)) {
                die("Especialidad inválida.<br>
         <a href='" . Enlaces::BASE_URL . "clinica/home/medicos'>Volver</a>");
            }

            // Teléfono
            if ($telefono !== '' && !preg_match('/^[0-9]{9}$/', $telefono)) {
                die("El teléfono debe tener 9 dígitos.<br>
         <a href='" . Enlaces::BASE_URL . "clinica/home/medicos'>Volver</a>");
            }

            // Email
            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                die("Email inválido.<br>
         <a href='" . Enlaces::BASE_URL . "clinica/home/medicos'>Volver</a>");
            }


            // FOTO
            $foto = $medico['foto_medico']; // mantener la actual

            if (!empty($_FILES['foto_medico']['name'])) {

                $directorioFisico = Enlaces::BASE_PATH . 'app/imagenes_registros/imagenes_medicos/';

                if (!is_dir($directorioFisico)) {
                    mkdir($directorioFisico, 0777, true);
                }

                $extension = strtolower(pathinfo($_FILES['foto_medico']['name'], PATHINFO_EXTENSION));

                $extPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
                if (!in_array($extension, $extPermitidas)) {
                    die("Formato de imagen no permitido");
                }

                $nombreArchivo = 'medico_' . uniqid() . '.' . $extension;
                $rutaFinal = $directorioFisico . $nombreArchivo;

                if (move_uploaded_file($_FILES['foto_medico']['tmp_name'], $rutaFinal)) {

                    // borrar foto anterior si existe
                    if (!empty($medico['foto_medico'])) {
                        $fotoAnterior = $directorioFisico . $medico['foto_medico'];
                        if (file_exists($fotoAnterior)) {
                            unlink($fotoAnterior);
                        }
                    }

                    $foto = $nombreArchivo;
                }
            }

            // Entrada de datos al modelo
            $medicoModel->setNombreMedico($nombre);
            $medicoModel->setApellidosMedico($apellidos);
            $medicoModel->setDniMedico($dni);
            $medicoModel->setNumeroColegiado($numero_colegiado);
            $medicoModel->setEspecialidadMedico($especialidad);
            $medicoModel->setTelefonoMedico($telefono);
            $medicoModel->setEmailMedico($email);
            $medicoModel->setFotoMedico($foto);

            if (!$medicoModel->actualizarMedico($pdo, $id_medico)) {
                die("Error al actualizar el médico");
            }

            header("Location: " . Enlaces::BASE_URL . "clinica/home/medicos");
            exit;
        }
    }
}
