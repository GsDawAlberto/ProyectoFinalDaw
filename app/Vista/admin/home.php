<?php 
use Mediagend\App\Config\Enlaces;
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: " . Enlaces::BASE_URL . "admin/login");
    exit;
}

$nombreUsuario = $_SESSION['admin']['nombre'];
?>

<h1>Panel de Administrador</h1>
<p>Bienvenido, <?= htmlspecialchars($nombreUsuario) ?>!</p>

<a href="<?= Enlaces::BASE_URL ?>admin/login">Cerrar sesión</a>
