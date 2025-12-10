<?php
namespace Mediagend\App\Config;

use PDO;
use PDOException;

// Carga de constantes correctamente
require_once __DIR__ . '/conexion.php';

class BaseDatos {

    private static ?PDO $connection = null;

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
                die("❌ Error de conexión a la BD: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
