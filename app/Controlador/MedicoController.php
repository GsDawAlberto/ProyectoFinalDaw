<?php

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Modelo\Medico;
use Mediagend\App\Config\BaseDatos;

class MedicoController
{
    /**************************** RUTA DE VISTAS **************************/

    /**************************** FORMULARIO LOGIN *************************/
    public function login()
    {
        require Enlaces::VIEW_PATH . "medico/login_medico.php";
    }

    /**************************** FORMULARIO REGISTRO *************************/
    public function loguear()
    {
        require Enlaces::VIEW_PATH . "medico/loguear_medico.php";
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
            header("Location: " . Enlaces::BASE_URL . "medico/loguear_medico");
            exit;
        }

        $fotoRuta = null;

        // Sanitizar entrada
        $nombre                 = trim(filter_input(INPUT_POST, 'nombre_medico', FILTER_SANITIZE_STRING));
        $apellidos              = trim(filter_input(INPUT_POST, 'apellidos_medico', FILTER_SANITIZE_STRING));
        $numero_colegiado       = trim(filter_input(INPUT_POST, 'numero_colegiado', FILTER_SANITIZE_STRING));
        $especialidad_medico    = trim(filter_input(INPUT_POST, 'especialidad_medico', FILTER_SANITIZE_EMAIL));
        $telefono               = trim(filter_input(INPUT_POST, 'telefono_medico', FILTER_SANITIZE_STRING));
        $email                  = trim(filter_input(INPUT_POST, 'email_medico', FILTER_SANITIZE_EMAIL));
        $fotoRuta               = trim($_FILES['foto_medico']['name']);
        $pass1                  = trim($_POST['password_medico'] ?? '');
        $pass2                  = trim($_POST['password2_medico'] ?? '');

        // Validar contraseñas
        if ($pass1 !== $pass2) {
            die("Las contraseñas no coinciden.<br><a href='" . Enlaces::BASE_URL . "medico/loguear_medico'>Volver</a>");
        }

        // Control de la ruta de la foto introducida
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
    public function acceder()
    {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . Enlaces::BASE_URL . "clinica/login_clinica");
            exit;
        }
        //Sanitizar entrada
        $colegiado = trim(filter_input(INPUT_POST, 'numero_colegiado', FILTER_SANITIZE_STRING) ?? '');
        $password = trim(filter_input(INPUT_POST, 'password_paciente', FILTER_SANITIZE_STRING) ?? '');

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
            'nombre_medico'         => $resultado['nombre_medico'],
            'apellidos_medico'      => $resultado['apellidos_medico'],
            'numero_colegiado'      => $resultado['numero_colegiado'],
            'telefono_medico'       => $resultado['telefono_medico'],
            'email_medico'          => $resultado['email_medico'],
            'especialidad_medico'   => $resultado['especialidad_medico']
        ];

        header("Location: " . Enlaces::BASE_URL . "medio/home_medico");
        exit;
    }

    /*************************  HOME MEDICO *************************/
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
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header("Location: " . Enlaces::BASE_URL . "medico/login_medico");
        exit;
    }

    /******************************* ELIMINAR MEDICO ***********************************/
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

    /* =======================
       MOSTRAR FORMULARIO
    ======================= */
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

    /* =======================
       GUARDAR CAMBIOS
    ======================= */
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

        // Sanitizar datos
        $nombre              = trim($_POST['nombre_medico']);
        $apellidos           = trim($_POST['apellidos_medico']);
        $numero_colegiado    = trim($_POST['numero_colegiado']);
        $especialidad        = trim($_POST['especialidad_medico']);
        $telefono            = trim($_POST['telefono_medico']);
        $email               = trim($_POST['email_medico']);

        // FOTO
        $foto = $medico['foto_medico']; // Mantener foto actual por defecto

        if (!empty($_FILES['foto_medico']['name'])) {
            $foto = uniqid() . '_' . $_FILES['foto_medico']['name'];
            move_uploaded_file(
                $_FILES['foto_medico']['tmp_name'],
                Enlaces::BASE_PATH . "app/imagenes_registros/imagenes_medicos/" . $foto
            );
        }

        // Setear modelo
        $medicoModel->setNombreMedico($nombre);
        $medicoModel->setApellidosMedico($apellidos);
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
