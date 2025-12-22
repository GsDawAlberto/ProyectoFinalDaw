<?php

namespace Mediagend\App\Modelo;

use PDO;
use PDOException;

class Clinica
{
    // Propiedades
    private ?int $id_clinica = null;
    private ?int $id_admin = null;
    private ?string $nombre_clinica = null;
    private ?string $direccion_clinica = null;
    private ?string $telefono_clinica = null;
    private ?string $email_clinica = null;
    private ?string $usuario_clinica = null;
    private ?string $password_clinica = null;

    // Getters
    /**
     * método para obtener el ID de la clínica
     * @return int|null
     */
    public function getIdClinica(): ?int
    {
        return $this->id_clinica;
    }
    /**
     * método para obtener el ID del administrador asociado a la clínica
     * @return int|null
     */
    public function getIdAdmin(): ?int
    {
        return $this->id_admin;
    }
    /**
     * método para obtener el nombre de la clínica
     * @return string|null
     */
    public function getNombreClinica(): ?string
    {
        return $this->nombre_clinica;
    }
    /**
     * método para obtener la dirección de la clínica
     * @return string|null
     */
    public function getDireccionClinica(): ?string
    {
        return $this->direccion_clinica;
    }
    /**
     * método para obtener el teléfono de la clínica
     * @return string|null
     */
    public function getTelefonoClinica(): ?string
    {
        return $this->telefono_clinica;
    }
    /**
     * método para obtener el email de la clínica
     * @return string|null
     */
    public function getEmailClinica(): ?string
    {
        return $this->email_clinica;
    }
    /**
     * método para obtener el usuario de la clínica
     * @return string|null
     */
    public function getUsuarioClinica(): ?string
    {
        return $this->usuario_clinica;
    }
    /**
     * método para obtener la contraseña de la clínica
     * @return string|null
     */
    public function getPasswordClinica(): ?string
    {
        return $this->password_clinica;
    }

    // Setters
    /**
     * método para establecer el ID de la clínica
     * @param int $id_clinica
     */
    public function setIdClinica(int $id_clinica): void
    {
        $this->id_clinica = $id_clinica;
    }
    /**
     * método para establecer el ID del administrador asociado a la clínica
     * @param int $id_admin
     */
    public function setIdAdmin(int $id_admin): void
    {
        $this->id_admin = $id_admin;
    }
    /**
     * método para establecer el nombre de la clínica
     * @param string $nombre_clinica
     */
    public function setNombreClinica(string $nombre_clinica): void
    {
        $this->nombre_clinica = $nombre_clinica;
    }
    /**
     * método para establecer la dirección de la clínica
     * @param string $direccion_clinica
     */
    public function setDireccionClinica(string $direccion_clinica): void
    {
        $this->direccion_clinica = $direccion_clinica;
    }
    /**
     * método para establecer el teléfono de la clínica
     * @param string $telefono_clinica
     */
    public function setTelefonoClinica(string $telefono_clinica): void
    {
        $this->telefono_clinica = $telefono_clinica;
    }

    /**
     * método para establecer el email de la clínica
     * @param string $email_clinica
     */
    public function setEmailClinica(string $email_clinica): void
    {
        $this->email_clinica = $email_clinica;
    }
    /**
     * método para establecer el usuario de la clínica
     * @param string $usuario_clinica
     */
    public function setUsuarioClinica(string $usuario_clinica): void
    {
        $this->usuario_clinica = $usuario_clinica;
    }
    /**
     * método para establecer la contraseña de la clínica
     * @param string $password_clinica
     */
    public function setPasswordClinica(string $password_clinica): void
    {
        $this->password_clinica = $password_clinica;
    }

    // MÉTODOS DE BASE DE DATOS
    /**
     * Método para guardar una nueva clínica en la base de datos
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @return string|int Retornamos el número de filas afectadas o ERR_CLINICA_01 al no guardar clínica
     */
    public function guardarClinica(PDO $pdo): string|int
    {
        //Intentamos capturar errores de PDO
        try {
            //Consulta SQL para insertar una nueva clínica
            $sql = "INSERT INTO clinica (id_admin, nombre_clinica, direccion_clinica, telefono_clinica, email_clinica, usuario_clinica, password_clinica) 
                    VALUES (:id_admin, :nombre_clinica, :direccion_clinica, :telefono_clinica, :email_clinica, :usuario_clinica, :password_clinica)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([

                ':id_admin'          => $this->id_admin,
                ':nombre_clinica'    => $this->nombre_clinica,
                ':direccion_clinica' => $this->direccion_clinica,
                ':telefono_clinica'  => $this->telefono_clinica,
                ':email_clinica'     => $this->email_clinica,
                ':usuario_clinica'   => $this->usuario_clinica,
                ':password_clinica'  => password_hash($this->password_clinica, PASSWORD_BCRYPT)
            ]);

            //Guardamos el ID autogenerado
            $this->id_clinica = (int)$pdo->lastInsertId();

            //Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();

            //Capturamos errores de PDO
        } catch (PDOException $e) {
            $error = 'ERR_CLINICA_01'; // Error al guardar clínica
            return $error;
        }
    }

    /**
     * Método para autenticar una clínica
     * @param PDO $pdo. Conexión PDO a la base de datos 
     * @param string $usuario_clinica. Usuario de la clínica
     * @param string $password_clinica. Contraseña de la clínica
     * @return string|array|bool Retorna un array con los datos de la clínica, o false si no se encuentra, o ERR_CLINICA_02 en caso de error
     */
    public function autenticarClinica(PDO $pdo, string $usuario_clinica, string $password_clinica): string|array|bool
    {
        // Intentamos capturar errores de PDO
        try {
            // Consulta SQL para obtener la clínica por su usuario
            $sql = "SELECT * FROM clinica WHERE usuario_clinica = :usuario_clinica";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(
                [':usuario_clinica' => $usuario_clinica]
            );

            //Guardamos el resultado en un array asociativo
            $clinica = $stmt->fetch(PDO::FETCH_ASSOC);

            //Verificamos si el usuario existe
            if (!$clinica) {
                return false; // Clinica no encontrado
            }

            // Verificamos la contraseña
            if (!password_verify($password_clinica, $clinica['password_clinica'])) {
                return false; // contraseña incorrecta
            }

            //Cargamos datos dentro del objeto
            $this->id_clinica = $clinica['id_clinica'];
            $this->nombre_clinica = $clinica['nombre_clinica'];
            $this->direccion_clinica = $clinica['direccion_clinica'];
            $this->telefono_clinica = $clinica['telefono_clinica'];
            $this->email_clinica = $clinica['email_clinica'];
            $this->usuario_clinica = $clinica['usuario_clinica'];
            $this->password_clinica = $clinica['password_clinica'];

            //Retornamos el array con los datos de la clínica
            return $clinica;

            // Capturamos errores de PDO
        } catch (PDOException $e) {
            $error = 'ERR_CLINICA_02'; // Error al autenticar clínica
            return $error;
        }
    }

    /**
     * Método para mostrar los datos de una clínica por su ID
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_clinica. ID de la clínica a mostrar
     * @return string|array|null Retorna un array con los datos de la clínica, null si no existe os ERR_CLINICA_03 en caso de error al mostrar clínica
     */
    public function mostrarClinica(PDO $pdo, ?int $id_clinica = null): string|array|null
    {
        // Intentamos capturar errores de PDO
        try {
            if ($id_clinica === null) {
                // si no se indica id, obtenemos TODAS las clínicas
                $sql = "SELECT * FROM clinica";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Consulta SQL para obtener la clínica por su ID
                $sql = "SELECT * FROM clinica WHERE id_clinica = :id_clinica";
                // Preparar y ejecutar la consulta
                $stmt = $pdo->prepare($sql);

                $stmt->execute(
                    [':id_clinica' => $id_clinica]
                );

                // Guardamos el resultado en un array asociativo
                $clinica = $stmt->fetch(PDO::FETCH_ASSOC);

                //Retonamos el array o null si no existe
                return $clinica ?: null;
            }
        } catch (PDOException $e) {
            $error = 'ERR_CLINICA_03'; // Error al mostrar clínica
            return $error;
        }
    }

    /**
     * Método para actualizar los datos de una clínica
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_clinica. ID de la clínica a actualizar
     * @return string|int Retorna el número de filas afectadas o ERR_CLINICA_04 en caso de error al actualizar clínica
     */
    public function actualizarClinica(PDO $pdo, int $id_clinica): string|int
    {
        // Intentamos capturar errores de PDO
        try {
            // Consulta SQL para actualizar la clínica
            $sql = "UPDATE clinica 
                    SET nombre_clinica = :nombre_clinica, 
                        direccion_clinica = :direccion_clinica, 
                        telefono_clinica = :telefono_clinica, 
                        email_clinica = :email_clinica, 
                        usuario_clinica = :usuario_clinica 
                    WHERE id_clinica = :id_clinica";

            // Preparar y ejecutar la consulta
            $stmt = $pdo->prepare($sql);

            // Ejecutar la consulta con los datos actualizados
            $stmt->execute([
                ':nombre_clinica'    => $this->nombre_clinica,
                ':direccion_clinica' => $this->direccion_clinica,
                ':telefono_clinica'  => $this->telefono_clinica,
                ':email_clinica'     => $this->email_clinica,
                ':usuario_clinica'   => $this->usuario_clinica,
                ':password_clinica'  => password_hash($this->password_clinica, PASSWORD_BCRYPT),
                ':id_clinica'       => $id_clinica
            ]);

            // Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();

            // Capturamos errores de PDO
        } catch (PDOException $e) {
            $error = 'ERR_CLINICA_04'; // Error al actualizar clínica
            return $error;
        }
    }

    /**
     * Método para eliminar una clínica por su ID
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_clinica. ID de la clínica a eliminar
     * @return string|int Retorna el número de filas afectadas o ERR_ADMIN_05 en caso de error al eliminar clínica
     */
    public function eliminarClinica(PDO $pdo, int $id_clinica): string|int
    {
        // Intentamos capturar errores de PDO
        try {
            //Consultamos SQL para eliminar un paciente por su id
            $sql = "DELETE FROM clinica WHERE id_clinica = :id_clinica";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_clinica' => $id_clinica
            ]);

            //Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();

            //Capturamos cualquier error de PDO
        } catch (PDOException $e) {
            //Devolvemos el mensaje de error
            $error = 'ERR_ADMIN_05'; // Error al eliminar clínica
            return $error;
        }
    }
}
