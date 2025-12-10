<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Mediagend\App\Rutas\Rutas;

// Ejecutar router
$rutas = new Rutas();
$rutas->getRuta();
