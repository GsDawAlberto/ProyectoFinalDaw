<?php

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Paciente;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Controlador\Helper;

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

    /**************************** VISTA HOME PACIENTE *************************/
    public function home_mis_citas()
    {
        session_start();

        if (!isset($_SESSION['paciente'])) {
            exit('Acceso denegado');
        }

        require Enlaces::VIEW_CONTENT_PACIENTE_PATH . "citas_pacientes.php";
    }

    public function home_mis_informes()
    {
        session_start();

        if (!isset($_SESSION['paciente'])) {
            exit('Acceso denegado');
        }

        require Enlaces::VIEW_CONTENT_PACIENTE_PATH . "mis_informes.php";
    }

    public function home_mis_ajustes()
    {
        session_start();

        if (!isset($_SESSION['paciente'])) {
            exit('Acceso denegado');
        }

        require Enlaces::VIEW_CONTENT_PACIENTE_PATH . "ajustes_pacientes.php";
    }

    public function home_inicio()
    {
        session_start();

        if (!isset($_SESSION['paciente'])) {
            exit('Acceso denegado');
        }

        require Enlaces::VIEW_CONTENT_PACIENTE_PATH . "inicio_pacientes.php";
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

        $fotoRuta = null;

        // Sanitizar entrada
        $nombre     = trim(filter_input(INPUT_POST, 'nombre_paciente', FILTER_SANITIZE_STRING));
        $apellidos  = trim(filter_input(INPUT_POST, 'apellidos_paciente', FILTER_SANITIZE_STRING));
        $dni        = trim(filter_input(INPUT_POST, 'dni_paciente', FILTER_SANITIZE_STRING));
        $telefono   = trim(filter_input(INPUT_POST, 'telefono_paciente', FILTER_SANITIZE_STRING));
        $email      = trim(filter_input(INPUT_POST, 'email_paciente', FILTER_SANITIZE_EMAIL));
        $usuario    = trim(filter_input(INPUT_POST, 'usuario_paciente', FILTER_SANITIZE_STRING));
        /* $id_medico  = trim($_POST['id_medico']) ?? null; */
        $fotoRuta = trim($_FILES['foto_paciente']['name']);
        $pass1      = trim($_POST['password_paciente'] ?? '');
        $pass2      = trim($_POST['password2_paciente'] ?? '');

        // Validar contraseñas
        if ($pass1 !== $pass2) {
            die("Las contraseñas no coinciden.<br><a href='" . Enlaces::BASE_URL . "paciente/loguear_paciente'>Volver</a>");
        }

        // Control de la ruta de la foto introducida
        if (!empty($_FILES['foto_paciente']['name'])) {

            // RUTA FÍSICA REAL
            $directorioFisico = Enlaces::BASE_PATH . 'app/imagenes_registros/imagenes_pacientes/';

            if (!is_dir($directorioFisico)) {
                mkdir($directorioFisico, 0777, true);
            }

            $extension = strtolower(pathinfo($_FILES['foto_paciente']['name'], PATHINFO_EXTENSION));

            $extPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($extension, $extPermitidas)) {
                die('Formato de imagen no permitido');
            }

            $nombreArchivo = 'paciente_' . time() . '.' . $extension;
            $rutaFisicaFinal = $directorioFisico . $nombreArchivo;

            if (move_uploaded_file($_FILES['foto_paciente']['tmp_name'], $rutaFisicaFinal)) {

                //SOLO guardamos la RUTA RELATIVA para la BD
                $fotoRuta = $nombreArchivo;
            }
        }

        // Imagen por defecto
        if (!$fotoRuta) {
            $fotoRuta = 'imagen_paciente_por_defecto.jpg';
        }

        // Conexión BD
        $pdo = BaseDatos::getConexion();

        // Crear modelo
        $paciente = new Paciente();
        $paciente->setIdClinica($idClinica);
        /* $paciente->setIdMedico($id_medico); */
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
            'id_medico'   => $resultado['id_medico'],
            'id_clinica'  => $resultado['id_clinica'],
            'nombre_paciente'      => $resultado['nombre_paciente'],
            'apellidos_paciente'   => $resultado['apellidos_paciente'],
            'dni_paciente'         => $resultado['dni_paciente'],
            'telefono_paciente'    => $resultado['telefono_paciente'],
            'email_paciente'       => $resultado['email_paciente'],
            'usuario_paciente'     => $resultado['usuario_paciente']
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
        $paciente = $pacienteModel->mostrarPacientePorId($pdo, $id_paciente);

        if ($paciente === 'ERR_PACIENTE_03') {
            die("Error al obtener el paciente");
        }

        if (!$paciente || !is_array($paciente)) {
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

    /************************* MODIFICAR PACIENTE *************************/
    public function modificar()
    {
        session_start();

        if (!isset($_SESSION['clinica'])) {
            header("Location: " . Enlaces::BASE_URL . "clinica/login_clinica");
            exit;
        }

        $pdo = BaseDatos::getConexion();
        $pacienteModel = new Paciente();

        /* =======================
       MOSTRAR FORMULARIO
    ======================= */
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $id_paciente = filter_input(INPUT_GET, 'id_paciente', FILTER_VALIDATE_INT);
            if (!$id_paciente) {
                header("Location: " . Enlaces::BASE_URL . "clinica/home/pacientes");
                exit;
            }

            $paciente = $pacienteModel->mostrarPacientePorId($pdo, $id_paciente);

            if (!$paciente) {
                die("Paciente no encontrado");
            }

            if ((int)$paciente['id_clinica'] !== (int)$_SESSION['clinica']['id_clinica']) {
                die("No tienes permisos para modificar este paciente");
            }

            require Enlaces::VIEW_PATH . "paciente/editar_paciente.php";
            exit;
        }

        /* =======================
       GUARDAR CAMBIOS
    ======================= */
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id_paciente = filter_input(INPUT_POST, 'id_paciente', FILTER_VALIDATE_INT) ; 
            if (!$id_paciente) {
                die("ID inválido");
            }

            $paciente = $pacienteModel->mostrarPacientePorId($pdo, $id_paciente);

            if (!$paciente) {
                die("Paciente no encontrado");
            }

            if ((int)$paciente['id_clinica'] !== (int)$_SESSION['clinica']['id_clinica']) {
                die("No tienes permisos");
            }

            // Datos
            $nombre    = trim($_POST['nombre_paciente']);
            $apellidos = trim($_POST['apellidos_paciente']);
            $dni       = trim($_POST['dni_paciente']);
            $telefono  = trim($_POST['telefono_paciente']);
            $email     = trim($_POST['email_paciente']);
            $id_medico  = trim($_POST['id_medico']) ?: null;
            $usuario   = trim($_POST['usuario_paciente']);

            // FOTO
            $foto = $paciente['foto_paciente'];

            if (!empty($_FILES['foto_paciente']['name'])) {
                $foto = uniqid() . '_' . $_FILES['foto_paciente']['name'];
                move_uploaded_file(
                    $_FILES['foto_paciente']['tmp_name'],
                    Enlaces::BASE_PATH . "app/imagenes_registros/imagenes_pacientes/" . $foto
                );
            }

            $pacienteModel->setNombrePaciente($nombre);
            $pacienteModel->setApellidosPaciente($apellidos);
            $pacienteModel->setDniPaciente($dni);
            $pacienteModel->setTelefonoPaciente($telefono);
            $pacienteModel->setEmailPaciente($email);
            $pacienteModel->setUsuarioPaciente($usuario);
            $pacienteModel->setIdMedico($id_medico);
            $pacienteModel->setFotoPaciente($foto);

            if (!$pacienteModel->actualizarPaciente($pdo, $id_paciente)) {
                die("Error al actualizar el paciente");
            }

            header("Location: " . Enlaces::BASE_URL . "clinica/home/pacientes");
            exit;
        }
    }

    /************************* MODIFICAR DATOS PACIENTE (POR PACIENTE) *************************/
public function modificar_mis_datos()
{
    session_start();

        if (!isset($_SESSION['paciente'])) {
            header("Location: " . Enlaces::BASE_URL . "paciente/login_paciente");
            exit;
        }

        $pdo = BaseDatos::getConexion();
        $pacienteModel = new Paciente();

        /* =======================
       MOSTRAR FORMULARIO
    ======================= */
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $id_paciente = filter_input(INPUT_GET, 'id_paciente', FILTER_VALIDATE_INT);
            if (!$id_paciente) {
                header("Location: " . Enlaces::BASE_URL . "paciente/home/ajustes");
                exit;
            }

            $paciente = $pacienteModel->mostrarPacientePorId($pdo, $id_paciente);

            if (!$paciente) {
                die("Paciente no encontrado");
            }

            if ((int)$paciente['id_clinica'] !== (int)$_SESSION['clinica']['id_clinica']) {
                die("No tienes permisos para modificar este paciente");
            }

            require Enlaces::VIEW_PATH . "paciente/ajustes_pacientes.php";
            exit;
        }

        /* =======================
       GUARDAR CAMBIOS
    ======================= */
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id_paciente = filter_input(INPUT_POST, 'id_paciente', FILTER_VALIDATE_INT) ; 
            if (!$id_paciente) {
                die("ID inválido");
            }

            $paciente = $pacienteModel->mostrarPacientePorId($pdo, $id_paciente);

            if (!$paciente) {
                die("Paciente no encontrado");
            }

            // Datos
            $telefono  = trim($_POST['telefono_paciente']);
            $email     = trim($_POST['email_paciente']);

            
            $pacienteModel->setTelefonoPaciente($telefono);
            $pacienteModel->setEmailPaciente($email);

            if (!$pacienteModel->actualizarPaciente($pdo, $id_paciente)) {
                die("Error al actualizar el paciente");
            }

            header("Location: " . Enlaces::BASE_URL . "paciente/home/ajustes");
            exit;
        }
}


    public function modificar_password()
{
    session_start();

    if (!isset($_SESSION['paciente'])) {
        header("Location: " . Enlaces::BASE_URL . "paciente/login_paciente");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: " . Enlaces::BASE_URL . "paciente/home/ajustes");
        exit;
    }

    $id_paciente = $_SESSION['paciente']['id_paciente'];

    $password_actual        = trim($_POST['password_actual'] ?? '');
    $nueva_password         = trim($_POST['nueva_password'] ?? '');
    $repetir_nueva_password = trim($_POST['repetir_nueva_password'] ?? '');

    // Validar que las nuevas contraseñas coincidan
    if ($nueva_password !== $repetir_nueva_password) {
        die("Las nuevas contraseñas no coinciden.<br>
            <a href='" . Enlaces::BASE_URL . "paciente/home/ajustes'>Volver</a>");
    }

    $pdo = BaseDatos::getConexion();
    $pacienteModel = new Paciente();

    // Obtener datos del paciente
    $paciente = $pacienteModel->mostrarPacientePorId($pdo, $id_paciente);
    if (!$paciente) {
        die("Paciente no encontrado.<br>
            <a href='" . Enlaces::BASE_URL . "paciente/home/ajustes'>Volver</a>");
    }

    // Verificar la contraseña actual
    if (!password_verify($password_actual, $paciente['password_paciente'])) {
        die("La contraseña actual introducida es incorrecta.<br>
            <a href='" . Enlaces::BASE_URL . "paciente/home/ajustes'>Volver</a>");
    }

    // Evitar que la nueva sea igual a la actual
    if (password_verify($nueva_password, $paciente['password_paciente'])) {
        die("La nueva contraseña no puede ser igual a la contraseña actual.<br>
            <a href='" . Enlaces::BASE_URL . "paciente/home/ajustes'>Volver</a>");
    }

    // Actualizar la contraseña usando el modelo
    $resultado = $pacienteModel->actualizarPassword($pdo, $id_paciente, $nueva_password);

    if (!$resultado) {
        die("Error al cambiar la contraseña.<br>
            <a href='" . Enlaces::BASE_URL . "paciente/home/ajustes'>Volver</a>");
    }

    header("Location: " . Enlaces::BASE_URL . "paciente/home/ajustes?msg=pass_actualizada");
    exit;
}
}
