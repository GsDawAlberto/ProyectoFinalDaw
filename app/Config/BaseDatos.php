<?php
/**
 * Archivo de configuración de conexión a base de datos
 *
 * Define la clase BaseDatos encargada de crear y mantener
 * una única conexión PDO usando el patrón Singleton.
 *
 * @package Mediagend\App\Config
 */

namespace Mediagend\App\Config;

use PDO;
use PDOException;

// Carga de constantes
require_once __DIR__ . '/conexion.php';

/**
 * Clase BaseDatos
 *
 * Gestiona la conexión a la base de datos mediante PDO.
 * Implementa un patrón Singleton para reutilizar una única
 * instancia de conexión durante la ejecución de la aplicación.
 */
class BaseDatos {

    /**
     * Instancia única de la conexión PDO
     *
     * @var PDO|null
     */
    private static ?PDO $connection = null;

    /**
     * Obtiene la conexión a la base de datos
     *
     * Si la conexión no existe, la crea usando las constantes
     * definidas en el archivo de configuración.
     *
     * @return PDO Instancia activa de conexión PDO
     */
    public static function getConexion(): PDO {

        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Error de conexión a la BD: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
