<?php

namespace Mediagend\App\Controlador;

use Mediagend\App\Config\Enlaces;
use Mediagend\App\Config\BaseDatos;
use Mediagend\App\Modelo\Informe;
use Mediagend\App\Modelo\Paciente;
// PDF
use TCPDF;

class InformeController
{
    /******************** FORMULARIO CREAR INFORME ********************/
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
            $paciente
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

        header("Location: " . Enlaces::BASE_URL . "medico/home/pacientes");
        exit;
    }

    /******************** GENERAR PDF ********************/
    private function generarPDF(
        string $rutaFinal,
        string $diagnostico,
        string $tratamiento,
        array $clinica,
        array $medico,
        array $paciente
    ) {
        $pdf = new TCPDF('P', 'mm', 'A4');
        $pdf->SetMargins(15, 20, 15);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);

        $diagnostico = nl2br(htmlspecialchars($diagnostico));
        $tratamiento = nl2br(htmlspecialchars($tratamiento));

        $html = <<<HTML
<style>
    h1 { text-align:center; font-size:18px; color:#003366; }
    .sub { text-align:center; font-size:11px; color:#555; }
    .box { border:1px solid #ccc; padding:10px; margin-bottom:12px; }
    .box_diagnostico { border:1px solid #ccc; padding:10px; margin-bottom:12px; background-color:#f9f9f9; height: 500px; }
    .box-tratamiento { border:1px solid #ccc; padding:10px; margin-bottom:12px; background-color:#f1f1f1; height: 500px; }
    .titulo { font-weight:bold; color:#005f87; margin-bottom:5px; }
    .pie { font-size:9px; text-align:center; color:#777; margin-top:20px; }
</style>

<h1>INFORME MÉDICO</h1>
<div class="sub">{$clinica['nombre_clinica']}</div>

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
    Documento médico generado automáticamente · Mediagend
</div>
HTML;

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($rutaFinal, 'F');
    }

public function listar()
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

    // Pasas datos reales a la vista
    require Enlaces::VIEW_PATH . 'informe/listar_informes.php';
}

public function ver()
{
    session_start();

    if (!isset($_SESSION['medico'])) {
        exit('Acceso denegado');
    }

    // Obtener el ID del informe
    $id_informe = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id_informe) {
        exit('Informe inválido');
    }

    // Conexión y obtención del informe
    $pdo = BaseDatos::getConexion();
    $informeModel = new Informe();
    $informe = $informeModel->mostrarInforme($pdo, $id_informe);

    if (!$informe) {
        exit('Informe no encontrado en la base de datos');
    }

    // Ruta física del archivo PDF
    $rutaPDF = Enlaces::BASE_PATH . 'app/imagenes_registros/informes_clinicos/' . $informe['archivo_pdf_informe'];

    // Validar que el archivo exista realmente
    if (!is_file($rutaPDF)) {
        exit('Archivo PDF no encontrado en la carpeta de informes');
    }

    // Enviar PDF al navegador
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($informe['archivo_pdf_informe']) . '"');
    header('Content-Length: ' . filesize($rutaPDF));

    // Mostrar archivo
    readfile($rutaPDF);
    exit;
}
}
