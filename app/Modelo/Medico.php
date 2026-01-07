<?php

namespace Mediagend\App\Modelo;

use PDO;
use PDOException;

class Medico
{
    //Propiedades
    private ?int $id_medico = null;
    private ?int $id_clinica = null;
    private ?string $nombre_medico = null;
    private ?string $apellidos_medico = null;
    private ?string $especialidad_medico = null;
    private ?string $numero_colegiado = null;
    private ?string $foto_medico = null;
    private ?string $telefono_medico = null;
    private ?string $email_medico = null;
    private ?string $password_medico = null;

    //Getters
    /**
     * Método para obener el ID del médico
     * @return int|null
     */
    public function getIdMedico(): ?int
    {
        return $this->id_medico;
    }
    /**
     * Método para obtener el ID de la clínica
     * @return int|null
     */
    public function getIdClinica(): ?int
    {
        return $this->id_clinica;
    }
    /**
     * Método para obtener el nombre del médico
     * @return string|null
     */
    public function getNombreMedico(): ?string
    {
        return $this->nombre_medico;
    }
    /**
     * Método para obtener los apellidos del médico
     * @return string|null
     */
    public function getApellidosMedico(): ?string
    {
        return $this->apellidos_medico;
    }
    /**
     * Método para obtener la especialidad del médico
     * @return string|null
     */
    public function getEspecialidadMedico(): ?string
    {
        return $this->especialidad_medico;
    }
    /**
     * Método para obtener el numero de colegiado del médico
     * @return string|null
     */
    public function getNumeroColegiado(): ?string
    {
        return $this->numero_colegiado;
    }
    /**
     * Método para obtener la ruta de imagen del médico
     * @return string|null
     */
    public function getFotoMedico(): ?string
    {
        return $this->foto_medico;
    }
    /**
     * Método para obtener el teléfono del médico
     * @return string|null
     */
    public function getTelefonoMedico(): ?string
    {
        return $this->telefono_medico;
    }
    /**
     * Método para obtener el email del médico
     * @return string|null
     */
    public function getEmailMedico(): ?string
    {
        return $this->email_medico;
    }
    /**
     * Método para obtener la contraseña del médico
     * @return string|null
     */
    public function getPasswordMedico(): ?string
    {
        return $this->password_medico;
    }

    //Setters
    /**
     * Método para establecer el ID del médico
     * @param int $id_medico
     */
    public function setIdMedico(int $id_medico): void
    {
        $this->id_medico = $id_medico;
    }
    /**
     * Método para establecer el ID de la clínica
     * @param int $id_clinica
     */
    public function setIdClinica(int $id_clinica): void
    {
        $this->id_clinica = $id_clinica;
    }
    /**
     * Método para establecer el nombre del médico
     * @param string $nombre_medico
     */
    public function setNombreMedico(string $nombre_medico): void
    {
        $this->nombre_medico = $nombre_medico;
    }
    /**
     * Método para establecer los apellidos del médico
     * @param string $apellidos_medico
     */
    public function setApellidosMedico(string $apellidos_medico): void
    {
        $this->apellidos_medico = $apellidos_medico;
    }
    /**
     * Método para establecer la especialidad del médico
     * @param string $especialidad_medico
     */
    public function setEspecialidadMedico(string $especialidad_medico): void
    {
        $this->especialidad_medico = $especialidad_medico;
    }
    /**
     * Método para establecer el teléfono del médico
     * @param string $telefono_medico
     */
    /**
     * Método para establecer el numero de colegiado del médico
     * @param string $numero_colegiado
     */
    public function setNumeroColegiado(string $numero_colegiado): void
    {
        $this->numero_colegiado = $numero_colegiado;
    }
    /**
     * Método para establecer la ruta de la foto del médico
     * @param string $foto_medico
     */
    public function setFotoMedico(string $foto_medico): void
    {
        $this->foto_medico = $foto_medico;
    }
    public function setTelefonoMedico(string $telefono_medico): void
    {
        $this->telefono_medico = $telefono_medico;
    }
    /**
     * Método para establecer el email del médico
     * @param string $email_medico
     */
    public function setEmailMedico(string $email_medico): void
    {
        $this->email_medico = $email_medico;
    }
    /**
     * Método para establecer la contraseña del médico
     * @param string $password_medico
     */
    public function setPasswordMedico(string $password_medico): void
    {
        $this->password_medico = $password_medico;
    }

    //MÉTODOS DE BASE DE DATOS
    /**
     * Método para guardar un médico en la base de datos
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @return string|int Retorna el número de filas afectadas o ERR_MEDICO_01 en caso de no poder guardar el médico
     */
    public function guardarMedico(PDO $pdo): string|int
    {
        try {
            $sql = "INSERT INTO medico (id_clinica, nombre_medico, apellidos_medico, numero_colegiado, especialidad_medico, telefono_medico, email_medico, foto_medico)
                    VALUES (:id_clinica, :nombre_medico, :apellidos_medico, :numero_colegiado, :especialidad_medico, :telefono_medico, :email_medico, :foto_medico)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':id_clinica' => $this->id_clinica,
                ':nombre_medico' => $this->nombre_medico,
                ':apellidos_medico' =>$this->apellidos_medico,
                ':numero_colegiado' =>$this->numero_colegiado,
                ':especialidad_medico' => $this->especialidad_medico,
                ':telefono_medico' => $this->telefono_medico,
                ':foto_medico' => $this->foto_medico,
                ':email_medico' => $this->email_medico
            ]);

            // Guardamos el ID autogenerado
            $this->id_medico = (int)$pdo->lastInsertId();

            // Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();

            // Capturamos cualquier error de PDO
        } catch (PDOException $e) {
            //Devolvemos el mensaje de error
            $error = 'ERR_MEDICO_01'; // Error al guardar médico
            return $error;
        }
    }

    public function autenticarMedico(PDO $pdo, string $numero_colegiado, string $password_medico): string|array|bool
    {
        // Intentamos capturar errores de PDO
        try {
            // Consulta SQL para buscar el médico por email
            $sql = "SELECT * FROM medico WHERE numero_colegiado = :numero_colegiado";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':numero_colegiado' => $numero_colegiado
            ]);

            // Guardamos el resultado en un array asociativo
            $medico = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificamos si el médico existe
            if (!$medico) {
                return false; // Médico no encontrado
            }

            // Verificamos la contraseña
            if (!password_verify($password_medico, $medico['password_medico'])) {
                return false; // Contraseña incorrecta
            }

            // Cargamos los datos del médico en el objeto
                $this->id_medico = (int)$medico['id_medico'];
                $this->id_clinica = (int)$medico['id_clinica'];
                $this->nombre_medico = $medico['nombre_medico'];
                $this->apellidos_medico = $medico['apellidos_medico'];
                $this->numero_colegiado = $medico['numero_colegiado'];
                $this->foto_medico = $medico['foto_medico'];
                $this->especialidad_medico = $medico['especialidad_medico'];
                $this->telefono_medico = $medico['telefono_medico'];
                $this->email_medico = $medico['email_medico'];
                $this->password_medico = $medico['password_medico'];

                // Retornamos el array con los datos del médico
                return $medico;

            // Capturamos cualquier error de PDO
        } catch (PDOException $e) {
            $error = 'ERR_MEDICO_02'; // Error al autenticar médico
            return $error;
        }
    }
    /**
     * Método para mostrar un médico por su ID o todos los médicos si no se proporciona ID
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int|null $id_medico. ID del médico a buscar (opcional)
     * @return string|array|null Retorna un array asociativo con los datos del médico, un array de médicos o ERR_MEDICO_03 en caso de error
     */
    public function mostrarMedico(PDO $pdo, ?int $id_medico = null): string|array|null
    {
        // Intentamos capturar errores de PDO
        try {
            if ($id_medico !== null) {
                // Consulta SQL para buscar el médico por ID
                $sql = "SELECT * FROM medico";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Consulta SQL para buscar el médico por ID
                $sql = "SELECT * FROM medico WHERE id_medico = :id_medico";
                // Preparamos y ejecutamos la consulta
                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    ':id_medico' => $id_medico
                ]);

                // Guardamos el resultado en un array asociativo
                $medico = $stmt->fetch(PDO::FETCH_ASSOC);

                // Retornamos el array o null si no existe
                return $medico ?: null;
            }
            // Capturamos cualquier error de PDO
        } catch (PDOException $e) {
            $error = 'ERR_MEDICO_03'; // Error al mostrar médico
            return $error;
        }
    }
    /**
     * Método para actualizar un médico en la base de datos
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_medico. ID del médico a actualizar
     * @return string|int Retorna el número de filas afectadas o ERR_MEDICO_04 en caso de no poder actualizar el médico
     */
    public function actualizarMedico(PDO $pdo, int $id_medico): string|int
    {
        // Intentamos capturar errores de PDO
        try {
            // Consulta SQL para actualizar el médico
            $sql = "UPDATE medico SET
                    id_clinica = :id_clinica,
                    nombre_medico = :nombre_medico,
                    apellidos_medico = :apellidos_medico,
                    numero_colegiado = :numero_colegiado,
                    especialidad_medico = :especialidad_medico,
                    telefono_medico = :telefono_medico,
                    email_medico = :email_medico,
                    foto_medico = :foto_medico
                    WHERE id_medico = :id_medico";

            // Preparamos y ejecutamos la consulta
            $stmt = $pdo->prepare($sql);

            // Ejecutamos la consulta con los datos del objeto
            $stmt->execute([
                ':id_clinica' => $this->id_clinica,
                ':nombre_medico' => $this->nombre_medico,
                ':apellidos_medico' =>$this->apellidos_medico,
                ':numero_colegiado' =>$this->numero_colegiado,
                ':especialidad_medico' => $this->especialidad_medico,
                ':telefono_medico' => $this->telefono_medico,
                ':email_medico' => $this->email_medico,
                ':foto_medico' =>$this->foto_medico,
                ':id_medico' => $id_medico
            ]);

            // Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();

        } catch (PDOException $e) {
            //Devolvemos el mensaje de error
            $error = 'ERR_MEDICO_04'; // Error al actualizar médico
            return $error;
        }
    }
    /**
     * Método para eliminar un médico de la base de datos
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_medico. ID del médico a eliminar
     * @return string|int Retorna el número de filas afectadas o ERR_MEDICO_05 en caso de no poder eliminar el médico
     */
    public function eliminarMedico(PDO $pdo, int $id_medico): string|int
    {
        // Intentamos capturar errores de PDO
        try {
            // Consulta SQL para eliminar el médico
            $sql = "DELETE FROM medico WHERE id_medico = :id_medico";

            // Preparamos y ejecutamos la consulta
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_medico' => $id_medico
            ]);

            // Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();

        } catch (PDOException $e) {
            //Devolvemos el mensaje de error
            $error = 'ERR_MEDICO_05'; // Error al eliminar médico
            return $error;
        }
    }
}