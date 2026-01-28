<?php

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Informe;
use Mediagend\App\Modelo\Paciente;
// PDF
use TCPDF;

/**
 * Controlador de Informes Médicos
 *
 * Gestiona la creación, guardado, listado, visualización y eliminación
 * de informes clínicos, incluyendo la generación de PDFs.
 *
 * @package Mediagend\App\Controlador
 */
class InformeController
{
    /******************** FORMULARIO CREAR INFORME ********************/
    /**
     * Muestra el formulario para crear un nuevo informe médico.
     *
     * @return void
     */
    public function crear()
    {
        session_start();

        if (!isset($_SESSION['medico'])) {
            exit('Acceso denegado');
        }

        $_SESSION['paciente_informe'] = [
            'id_paciente' => $_POST['id_paciente']
        ];

        require Enlaces::VIEW_PATH . 'informe/crear_informe.php';
    }

    /******************** GUARDAR INFORME + PDF ********************/
    /**
     * Procesa y guarda un informe médico, generando un PDF
     *
     * Valida datos, genera el PDF, guarda los datos en base de datos
     * y redirige al listado de pacientes.
     *
     * @return void
     */
    public function guardar()
    {
        session_start();

        if (!isset($_SESSION['medico'], $_SESSION['clinica'])) {
            header("Location: " . Enlaces::BASE_URL . "medico/login_medico");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . Enlaces::BASE_URL . "medico/home_medico");
            exit;
        }

        /* ===== IDS ===== */
        $id_medico   = $_SESSION['medico']['id_medico'];
        $id_clinica  = $_SESSION['clinica']['id_clinica'];
        $id_paciente = filter_input(INPUT_POST, 'id_paciente', FILTER_VALIDATE_INT);
        $id_cita     = filter_input(INPUT_POST, 'id_cita', FILTER_VALIDATE_INT) ?? null;

        /* ===== DATOS DEL INFORME ===== */
        $diagnostico = trim($_POST['diagnostico'] ?? '');
        $tratamiento = trim($_POST['tratamiento'] ?? '');

        if (!$id_paciente) {
            die("Paciente inválido");
        }

        if ($diagnostico === '' || $tratamiento === '') {
            die("Diagnóstico y tratamiento obligatorios");
        }

        /* ===== GENERAR PDF ===== */
        $nombrePDF = 'informe_' . time() . '.pdf';
        $rutaCarpeta = Enlaces::BASE_PATH . 'app/imagenes_registros/informes_clinicos/';
        $horaVisita = date('Y-m-d H:i:s');

        // Crear la carpeta si no existe

        if (!is_dir($rutaCarpeta)) {
            mkdir($rutaCarpeta, 0777, true);
        }

        $pdo = BaseDatos::getConexion();
        $pacienteModel = new Paciente();

        $paciente = $pacienteModel->mostrarPacientePorId($pdo, $id_paciente);

        if (!$paciente) {
            die("Paciente no encontrado");
        }

        $this->generarPDF(
            $rutaCarpeta . $nombrePDF,
            $diagnostico,
            $tratamiento,
            $_SESSION['clinica'],
            $_SESSION['medico'],
            $paciente,
            $horaVisita

        );

        /* ===== GUARDAR EN BD ===== */
        $pdo = BaseDatos::getConexion();
        $informe = new Informe();

        $informe->setIdClinica($id_clinica);
        $informe->setIdMedico($id_medico);
        $informe->setIdPaciente($id_paciente);
        $informe->setIdCita($id_cita);
        $informe->setDiagnosticoInforme($diagnostico);
        $informe->setTratamientoInforme($tratamiento);
        $informe->setArchivoPdfInforme($nombrePDF);

        if (!$informe->guardarInforme($pdo)) {
            die("Error al guardar el informe");
        }


        /********************************** REDIRECCIONAR AL HOME DE PACIENTES **********************************/

        header("Location: " . Enlaces::BASE_URL . "medico/home/pacientes");
        exit;
    }

    /************************************ GENERAR PDF *******************************************/
    /**
     * Genera un PDF del informe médico
     *
     * @param string $rutaFinal Ruta completa del archivo PDF a generar
     * @param string $diagnostico Texto del diagnóstico
     * @param string $tratamiento Texto del tratamiento
     * @param array $clinica Datos de la clínica
     * @param array $medico Datos del médico
     * @param array $paciente Datos del paciente
     * @param string $horaVisita Fecha y hora de creación del informe
     *
     * @return void
     */
    private function generarPDF(
        string $rutaFinal,
        string $diagnostico,
        string $tratamiento,
        array $clinica,
        array $medico,
        array $paciente,
        string $horaVisita
    ) {
        $pdf = new TCPDF('P', 'mm', 'A4');
        $pdf->SetMargins(15, 20, 15);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);

        $diagnostico = nl2br(htmlspecialchars($diagnostico));
        $tratamiento = nl2br(htmlspecialchars($tratamiento));

        // CREACIÓN DEL MAQUETADO DE LA VISTA PDF

        $html = <<<HTML
            <style>
                h1 { text-align:center; font-size:18px; color:#003366; }
                h2 { text-align:center; font-size:16px; color:#004c73; }
                .logo-clinica { text-align:center; margin-bottom:10px; }
                .sub { text-align:center; font-size:11px; color:#555; }
                .box { border:1px solid #ccc; padding:10px; margin-bottom:12px; }
                .box_diagnostico { border:1px solid #ccc; padding:10px; margin-bottom:12px; background-color:#f9f9f9; height: 500px; }
                .box-tratamiento { border:1px solid #ccc; padding:10px; margin-bottom:12px; background-color:#f1f1f1; height: 500px; }
                .titulo { font-weight:bold; color:#005f87; margin-bottom:5px; }
                .pie { font-size:9px; text-align:center; color:#777; margin-top:20px; }
            </style>

            <h1>INFORME MÉDICO</h1>

            <div class="sub"><h2>{$clinica['nombre_clinica']}</h2></div>

            <br>
                <h3>Datos del Médico</h3>
                <div class="box">
                    <div class="titulo">Nombre y Apellidos</div>
                    {$medico['nombre_medico']} {$medico['apellidos_medico']}<br>
                    <div class="titulo">Nº Colegiado</div>
                    {$medico['numero_colegiado']}
                </div>

            <h3>Datos del Paciente</h3>
            <div class="box">
                <div class="titulo">Nombre y Apellidos</div>
                {$paciente['nombre_paciente']} {$paciente['apellidos_paciente']}<br>
                <div class="titulo">Codigo de paciente</div>
                {$paciente['usuario_paciente']}
            </div>

            <div class="box">
            <div class="box_diagnostico">
                <div class="titulo">Diagnóstico</div>
                {$diagnostico}
            </div>

            <div class="box-tratamiento">
                <div class="titulo">Tratamiento</div>
                {$tratamiento}
            </div>
            </div>
            <div class="pie">
                <h3>{$clinica['nombre_clinica']} · Informe generado el {$horaVisita}</h3>
                <p>Documento médico generado automáticamente · Mediagend ©</p>
            </div>
            HTML;

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($rutaFinal, 'F');
    }
    /**
     * Lista todos los informes de un paciente
     *
     * @return void
     */    public function listar()
    {
        session_start();

        if (!isset($_SESSION['medico'])) {
            header("Location: " . Enlaces::BASE_URL . "medico/login_medico");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . Enlaces::BASE_URL . "medico/home/pacientes");
            exit;
        }

        $id_paciente = filter_input(INPUT_POST, 'id_paciente', FILTER_VALIDATE_INT);

        if (!$id_paciente) {
            exit('Paciente inválido');
        }

        $pdo = BaseDatos::getConexion();
        $informeModel = new Informe();

        $informes = $informeModel->listarPorPaciente($pdo, $id_paciente);

        // Pasamos los datos reales a la vista
        require Enlaces::VIEW_PATH . 'informe/listar_informes.php';
    }
    /**
     * Muestra un informe médico en PDF
     *
     * @return void
     */    public function ver()
    {
        session_start();

        if (!isset($_SESSION['medico']) && !isset($_SESSION['paciente'])) {
            exit('Acceso denegado');
        }

        // ID del informe
        $idInforme = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$idInforme) {
            exit('Informe inválido');
        }

        $pdo = BaseDatos::getConexion();
        $informeModel = new Informe();

        /* Obtener informe con paciente y clínica */
        $informe = $informeModel->listarPorId($pdo, $idInforme);

        if (!$informe) {
            exit('Informe no encontrado');
        }


        // SEGURIDAD

        //  PACIENTE
        if (isset($_SESSION['paciente'])) {

            if ($informe['id_paciente'] != $_SESSION['paciente']['id_paciente']) {
                exit('Acceso no autorizado');
            }
        }

        //  MÉDICO
        elseif (isset($_SESSION['medico'])) {

            if ($informe['id_clinica'] != $_SESSION['clinica']['id_clinica']) {
                exit('Acceso no autorizado');
            }
        }


        // MOSTRAR PDF

        $rutaPDF = Enlaces::BASE_PATH . 'app/imagenes_registros/informes_clinicos/' . $informe['archivo_pdf_informe'];

        if (!is_file($rutaPDF)) {
            exit('Archivo PDF no encontrado');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($rutaPDF) . '"');
        header('Content-Length: ' . filesize($rutaPDF));

        readfile($rutaPDF);
        exit;
    }
    /**
     * Elimina un informe médico y su archivo PDF asociado
     *
     * @return void
     */
    public function eliminar()
    {
        session_start();

        if (!isset($_SESSION['medico'])) {
            header("Location: " . Enlaces::BASE_URL . "medico/login_medico");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . Enlaces::BASE_URL . "medico/home/pacientes");
            exit;
        }

        // Sanitizado
        $id_informe = filter_input(INPUT_POST, 'id_informe', FILTER_VALIDATE_INT);

        if (!$id_informe) {
            exit('Informe inválido');
        }

        $pdo = BaseDatos::getConexion();
        $informeModel = new Informe();

        // Obtener el informe para borrar el archivo PDF
        $informe = $informeModel->mostrarInforme($pdo, $id_informe);
        if ($informe && is_file(Enlaces::BASE_PATH . 'app/imagenes_registros/informes_clinicos/' . $informe['archivo_pdf_informe'])) {
            unlink(Enlaces::BASE_PATH . 'app/imagenes_registros/informes_clinicos/' . $informe['archivo_pdf_informe']);
        }

        // Eliminar el registro del informe en la base de datos
        if (!$informeModel->eliminarInforme($pdo, $id_informe)) {
            exit('Error al eliminar el informe');
        }

        header("Location: " . Enlaces::BASE_URL . "medico/home/pacientes");
        exit;
    }
}
