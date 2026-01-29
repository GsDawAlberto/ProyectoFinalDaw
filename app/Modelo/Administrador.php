<?php
/**
 * Método del módulo Administrador
 *
 * Gestiona los registros, accesos, modificaciones y eliminciones de la BD
 *
 * @package Mediagend\App\Controlador
 */
namespace Mediagend\App\Modelo;

use PDO;
use PDOException;
/**
 * Clase Administrador
 *
 * Representa a un administrador del sistema y proporciona métodos
 * para gestionar sus datos y operaciones en la base de datos.
 *
 * @package Mediagend\App\Modelo
 */
class Administrador
{
    // Propiedades
    private ?int $id_admin = null;
    private ?string $nombre_admin = null;
    private ?string $apellidos_admin = null;
    private ?string $dni_admin = null;
    private ?string $email_admin = null;
    private ?string $usuario_admin = null;
    private ?string $password_admin = null;

    //////////////////////////////////// Getters  /////////////////////////////
    /**
     * método para obtener el ID del administrador
     * @return int|null
     */
    public function getIdAdmin(): ?int
    {
        return $this->id_admin;
    }
    /**
     * método para obtener el nombre del administrador
     * @return string|null
     */
    public function getNombreAdmin(): ?string
    {
        return $this->nombre_admin;
    }
    /**
     * método para obtener los apellidos del administrador
     * @return string|null
     */
    public function getApellidosAdmin(): ?string
    {
        return $this->apellidos_admin;
    }
    /**
     * método para obterner el dni del administrador
     * @return string|null
     */
    public function getDniAdmin(): ?string
    {
        return $this->dni_admin;
    }
    /**
     * método para obtener el email del administrador
     * @return string|null
     */
    public function getEmailAdmin(): ?string
    {
        return $this->email_admin;
    }
    /**
     * método para obtener el usuario del administrador
     * @return string|null
     */
    public function getUsuarioAdmin(): ?string
    {
        return $this->usuario_admin;
    }
    /**
     * método para obtener la contraseña del administrador
     * @return string|null
     */
    public function getPasswordAdmin(): ?string
    {
        return $this->password_admin;
    }

    ////////////////////////////// Setters ///////////////////////////////////////
    /**
     * método para establecer el ID del administrador
     * @param int $id_admin
     */
    public function setIdAdmin(int $id_admin): void
    {
        $this->id_admin = $id_admin;
    }
    /**
     * método para establecer el nombre del administrador
     * @param string $nombre_admin
     */
    public function setNombreAdmin(string $nombre_admin): void
    {
        $this->nombre_admin = $nombre_admin;
    }
    /**
     * método para establecer los apellidos del administrador
     * @param string $apellidos_admin
     */
    public function setApellidosAdmin(string $apellidos_admin): void
    {
        $this->apellidos_admin = $apellidos_admin;
    }
    /**
     * método para establecer el dni del administrador
     * @param string $dni_admin
     */
    public function setDniAdmin($dni_admin): void
    {
        $this->dni_admin = $dni_admin;
    }
    /**
     * método para establecer el email del administrador
     * @param string $email_admin
     */
    public function setEmailAdmin(string $email_admin): void
    {
        $this->email_admin = $email_admin;
    }
    /**
     * método para establecer el usuario del administrador
     * @param string $usuario_admin
     */
    public function setUsuarioAdmin(string $usuario_admin): void
    {
        $this->usuario_admin = $usuario_admin;
    }
    /**
     * método para establecer la contraseña del administrador
     * @param string $password_admin
     */
    public function setPasswordAdmin(string $password_admin): void
    {
        $this->password_admin = $password_admin;
    }

    // MÉTODOS DE BASE DE DATOS

    /**
     * Método para guardar un nuevo administrador en la base de datos
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @return string|int Retorna el ID del nuevo administrador o ERR_ADMIN_01, error al no guardar administrador
     */
    public function guardarAdmin(PDO $pdo): string|int
    {
        // Intentamos capturar errores de PDO
        try {
            // Consulta SQL para insertar un nuevo administrador
            $sql = "INSERT INTO administrador 
                        (nombre_admin, apellidos_admin, dni_admin, email_admin, usuario_admin, password_admin) 
                    VALUES 
                        (:nombre_admin, :apellidos_admin, :dni_admin, :email_admin, :usuario_admin, :password_admin)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre_admin'  => $this->nombre_admin,
                ':apellidos_admin' => $this->apellidos_admin,
                ':dni_admin' => $this->dni_admin,
                ':email_admin'   => $this->email_admin,
                ':usuario_admin' => $this->usuario_admin,
                ':password_admin' => password_hash($this->password_admin, PASSWORD_BCRYPT)
            ]);

            // Guardamos el ID autogenerado
            $this->id_admin = $pdo->lastInsertId();

            // Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();

            // Capturamos errores de PDO
        } catch (PDOException $e) {
            die('ERR_ADMIN_1' . $e->getMessage());// Error al guardar administrador
        }
    }

    /**
     * Método para autenticar un administrador
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param string $usuario_admin. Nombre de usuario del administrador
     * @param string $password_admin. Contraseña del administrador
     * @return string|array|bool. Retorna un array con los datos del administrador si la autenticación es exitosa, false si no existe usuario, 'ERR_ADMIN_02' en caso de error al autenticar
     */
    public function autenticarAdmin(PDO $pdo, string $usuario_admin, string $password_admin): string|array|bool
    {
        // Intentamos capturar errores de PDO
        try {
            // Consulta para obtener el administrador por su usuario
            $sql = "SELECT * FROM administrador WHERE usuario_admin = :usuario_admin";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':usuario_admin' => $usuario_admin
            ]);

            // Guardamos el resultado en un array asociativo
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si el usuario existe
            if (!$admin) {
                return false; // usuario no existe
            }

            // Verificar contraseña
            if (!password_verify($password_admin, $admin['password_admin'])) {
                return false; // contraseña incorrecta
            }

            // Cargar datos dentro del objeto
            $this->id_admin         = $admin['id_admin'];
            $this->nombre_admin     = $admin['nombre_admin'];
            $this->apellidos_admin  = $admin['apellidos_admin'];
            $this->email_admin      = $admin['email_admin'];
            $this->usuario_admin    = $admin['usuario_admin'];

            // Retornamos el array con los datos del administrador
            return $admin;

            // Capturamos errores de PDO
        } catch (PDOException $e) {
            die('ERR_ADMIN_2' . $e->getMessage());// Error al autenticar administrador
        }
    }

    /**
     * Método para mostrar los datos de un administrador por su ID
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_admin. ID del administrador a mostrar
     * @return string|array|null. Retorna un array con los datos del administrador, null si no existe, 'ERR_ADMIN_03' en caso de error al mostrar administrador
     */
    public function mostrarAdmin(PDO $pdo, ?int $id_admin = null): string|array|null
    {
        // Intentamos capturar errores de PDO
        try {
            if ($id_admin === null) {
                // si no se indica id, obtenemos TODAS los administradores
                $sql = "SELECT * FROM administrador";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            // Consulta para obtener el administrador por su ID
            $sql = "SELECT * FROM administrador WHERE id_admin = :id_admin";

            // Preparar y ejecutar la consulta
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':id_admin' => $id_admin
            ]);

            // Obtener el resultado como un array asociativo
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Retornar el array o null si no existe
            return $admin ?: null;
        }
        } catch (PDOException $e) {
            die('ERR_ADMIN_3' . $e->getMessage());// Error al mostrar administrador
        }
    }

    /**
     * Método para actualizar los datos de un administrador
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_admin. ID del administrador a actualizar
     * @return int|string. Retorna el número de filas afectadas o 'ERR_ADMIN_04' en caso de error al actualizar administrador
     */
    public function actualizarAdmin(PDO $pdo, int $id_admin): string|int
    {
        // Intentamos capturar errores de PDO
        try {
            // Consulta SQL para actualizar el administrador
            $sql = "UPDATE administrador
                    SET nombre_admin  = :nombre_admin,
                        apellidos_admin = :apellidos_admin,
                        dni_admin = :dni_admin,
                        email_admin   = :email_admin,
                        usuario_admin = :usuario_admin,
                        password_admin = :password_admin
                    WHERE id_admin = :id_admin";

            // Preparar y ejecutar la consulta
            $stmt = $pdo->prepare($sql);

            // Ejecutamos la consulta con los datos del objeto
            $stmt->execute([
                ':nombre_admin'     => $this->nombre_admin,
                ':apellidos_admin'  => $this->apellidos_admin,
                ':dni_admin'        => $this->dni_admin,
                ':email_admin'      => $this->email_admin,
                ':usuario_admin'    => $this->usuario_admin,
                ':password_admin'   => password_hash($this->password_admin, PASSWORD_BCRYPT),
                ':id_admin'         => $id_admin
            ]);

            // Retornamos true si se actualizó al menos una fila
            return true;

            // Capturamos errores de PDO
        } catch (PDOException $e) {
            die('ERR_ADMIN_4' . $e->getMessage());// Error al actualizar administrador
        }
    }

    /**
     * Método para eliminar un administrador por su ID
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_admin. ID del administrador a eliminar
     * @return int|string. Retorna el número de filas afectadas o 'ERR_ADMIN_05' en caso de error al eliminar administrador
     */
    public function eliminarAdmin(PDO $pdo, int $id_admin): string|int
    {
        try {
            $sql = "DELETE FROM administrador WHERE id_admin = :id_admin";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_admin' => $id_admin]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            die('ERR_ADMIN_5' . $e->getMessage()); // Error al eliminar administrador
        }
    }
   
}


