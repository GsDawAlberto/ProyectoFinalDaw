<?php

/**
 * Archivo de entrada principal (front controller) de la aplicación.
 *
 * Este script carga automáticamente las dependencias mediante Composer,
 * inicializa el router y ejecuta la ruta correspondiente según la URL
 * solicitada por el cliente.
 *
 * @package Mediagend
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Mediagend\App\Rutas\Rutas;

/**
 * Inicialización y ejecución del router
 *
 * Crea una instancia de la clase Rutas y llama al método getRuta(),
 * que se encarga de determinar qué controlador y acción ejecutar
 * en función de la URL solicitada.
 */
$rutas = new Rutas();
$rutas->getRuta();