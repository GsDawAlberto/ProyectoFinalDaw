<?php

namespace Mediagend\App\Modelo;

use PDO;
use PDOException;

class Paciente
{
    //Propiedades
    private ?int $id_paciente = null;
    private ?int $id_clinica = null;
    private ?string $nombre_paciente = null;
    private ?string $apellidos_paciente = null;
    private ?string $dni_paciente = null;
    private ?string $telefono_paciente = null;
    private ?string $email_paciente = null;
    private ?string $usuario_paciente = null;
    private ?string $password_paciente = null;
    private ?string $foto_paciente = null;
    
    //Getters
    /**
     * Método para obtener el ID del paciente
     * @return int|null
     */
    public function getIdPaciente(): ?int
    {
        return $this->id_paciente;
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
     * Método para obtener el nombre del paciente
     * @return string|null
     */
    public function getNombrePaciente(): ?string
    {
        return $this->nombre_paciente;
    }
    /**
     * Método para obtener los apellidos del paciente
     * @return string|null
     */
    public function getApellidosPaciente(): ?string
    {
        return $this->apellidos_paciente;
    }
    /**
     * Método para obtener el DNI del paciente
     * @return string|null
     */
    public function getDniPaciente(): ?string
    {
        return $this->dni_paciente;
    }
    /**
     * Método para obtener el teléfono del paciente
     * @return string|null
     */
    public function getTelefonoPaciente(): ?string
    {
        return $this->telefono_paciente;
    }
    /**
     * Método para obtener el email del paciente
     * @return string|null
     */
    public function getEmailPaciente(): ?string
    {
        return $this->email_paciente;
    }
    /**
     * Método para obtener el usuario del paciente
     * @return string|null
     */
    public function getUsuarioPaciente(): ?string
    {
        return $this->usuario_paciente;
    }
    /**
     * Método para obtener la contraseña del paciente
     * @return string|null
     */
    public function getPasswordPaciente(): ?string
    {
        return $this->password_paciente;
    }
    /**
     * Método para obtener la ruta de la foto del paciente
     * @return string|null
     */
    public function getFotoPaciente(): ?string
    {
        return $this->foto_paciente;
    }

    //////////////////////////// Setters //////////////////////////
    /**
     * Método para establecer el ID del paciente
     * @param int|null $id_paciente
     */
    public function setIdPaciente(int $id_paciente): void
    {
        $this->id_paciente = $id_paciente;
    }
    /**
     * Método para establecer el ID de la clínica
     * @param int|null $id_clinica
     */
    public function setIdClinica(int $id_clinica): void
    {
        $this->id_clinica = $id_clinica;
    }
    /**
     * Método para establecer el nombres del paciente
     * @param string $nombre
     */
    public function setNombrePaciente(string $nombre_paciente): void
    {
        $this->nombre_paciente = $nombre_paciente;
    }
    /**
     * Método para establecer los apellidos del paciente
     * @param string $apellidos
     */
    public function setApellidosPaciente(string $apellidos_paciente): void
    {
        $this->apellidos_paciente = $apellidos_paciente;
    }
    /**
     * Método para establecer el DNI del paciente
     * @param string $dni
     */
    public function setDniPaciente(string $dni_paciente): void
    {
        $this->dni_paciente = $dni_paciente;
    }
    /**
     * Método para establecer el teléfono del paciente
     * @param string $telefono
     */
    public function setTelefonoPaciente(string $telefono_paciente): void
    {
        $this->telefono_paciente = $telefono_paciente;
    }
    /**
     * Método para establecer el email del paciente
     * @param string $email
     */
    public function setEmailPaciente(string $email_paciente): void
    {
        $this->email_paciente = $email_paciente;
    }
    /**
     * Método para establecer el usuario del paciente
     * @param string $email
     */
    public function setUsuarioPaciente(string $usuario_paciente): void
    {
        $this->usuario_paciente = $usuario_paciente;
    }
    /**
     * Método para establecer la contraseña del paciente
     * @param string $password_paciente
     */
    public function setPasswordPaciente(string $password_paciente): void
    {
        $this->password_paciente = $password_paciente;
    }
    /**
     * Método para establecer la ruta de la foto del paciente
     * @param string $password_paciente
     */
    public function setFotoPaciente(string $foto_paciente): void
    {
        $this->foto_paciente = $foto_paciente;
    }

    // MÉTODOS DE BASE DE DATOS
    /**
     * Método para guardar un nuevo paciente en la base de datos
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @return string|int Retronamos el número de filas afectadas o ERR_PACIENTE_01 en caso de no poder guardar el paciente
     */

    public function guardarPaciente(PDO $pdo): string|int
    {
        //Intentamos capturar errores del PDO
        try {
            //Consulta SQL para insertar un nuevo paciente
            $sql = "INSERT INTO paciente (id_clinica, nombre_paciente, apellidos_paciente, dni_paciente, telefono_paciente, email_paciente, usuario_paciente, password_paciente, foto_paciente) 
                    VALUES (:id_clinica, :nombre_paciente, :apellidos_paciente, :dni_paciente, :telefono_paciente, :email_paciente, :usuario_paciente, :password_paciente, :foto_paciente)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':id_clinica' => $this->id_clinica,
                ':nombre_paciente' => $this->nombre_paciente,
                ':apellidos_paciente' => $this->apellidos_paciente,
                ':dni_paciente' => $this->dni_paciente,
                ':telefono_paciente' => $this->telefono_paciente,
                ':email_paciente' => $this->email_paciente,
                ':usuario_paciente' => $this->usuario_paciente,
                'password_paciente' => password_hash($this->password_paciente, PASSWORD_BCRYPT),
                'foto_paciente' => $this->foto_paciente
            ]);

            //Guardamos el ID autogenerado
            $this->id_paciente = (int)$pdo->lastInsertId();

            //Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();

            //Capturamos cualquier error de PDO
        } catch (PDOException $e) {
            //Devolvemos el mensaje de error
            die($e->getMessage());
            /*  $error = 'ERR_PACIENTE_01'; // Error al guardar paciente
            return $error . $e; */
        }
    }
    /**
     * Método para autenticar un paciente en la base de datos
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param string $email_paciente. Email del paciente
     * @param string $password_paciente. Contraseña del paciente
     * @return string|array|bool Retornamos un array con los datos del paciente, false si no se encuentra o ERR_PACIENTE_02 en caso de error
     */
    public function autenticarPaciente(PDO $pdo, string $email_paciente, string $password_paciente): string|array|bool
    {
        //Intentamos capturar errores del PDO
        try {
            //Consulta SQL para autenticar un paciente
            $sql = "SELECT * FROM paciente WHERE email_paciente = :email_paciente";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(
                [':email_paciente' => $email_paciente]
            );

            //Guardamos el resultado en un array asociativo
            $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

            //Verificamos se el usuario existe
            if (!$paciente) {
                return false; // Paciente no encontrado
            }

            //Verificamos la contraseña
            if (!password_verify($password_paciente, $paciente['password_paciente'])) {
                return false; // Contraseña incorrecta
            }

            // Cargamos datos dentro del objeto Paciente
            $this->id_paciente = (int)$paciente['id_paciente'];
            $this->id_clinica = (int)$paciente['id_clinica'];
            $this->nombre_paciente = $paciente['nombre_paciente'];
            $this->apellidos_paciente = $paciente['apellidos_paciente'];
            $this->dni_paciente = $paciente['dni_paciente'];
            $this->telefono_paciente = $paciente['telefono_paciente'];
            $this->email_paciente = $paciente['email_paciente'];
            $this->usuario_paciente = $paciente['usuario_paciente'];
            $this->password_paciente = $paciente['password_paciente'];
            $this->foto_paciente = $paciente['foto_paciente'];

            // Retornamos el array con los datos del paciente
            return $paciente;

            // Capturamos cualquier error de PDO
        } catch (PDOException $e) {
            $error = 'ERR_PACIENTE_02'; // Error al autenticar paciente
            return $error;
        }
    }
    /**
     * Método para mostrar los datos de un paciente por algún dato o todos los pacientes
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param mixed $busqueda. dato del paciente a mostrar
     * @return string|array|null Retorna un array con los datos del paciente, null si no existe o ERR_PACIENTE_03 en caso de error al mostrar pacientes
     */
    public function mostrarPaciente(PDO $pdo, ?string $busqueda = null): string|array|null
    {
        try {

            // SIN BÚSQUEDA → TODOS LOS PACIENTES
            if ($busqueda === null || trim($busqueda) === '') {

                $sql = "SELECT * FROM paciente";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // CON BÚSQUEDA → BUSCAR EN VARIOS CAMPOS
            $sql = "
            SELECT * FROM paciente
            WHERE nombre_paciente     LIKE :busqueda
               OR apellidos_paciente  LIKE :busqueda
               OR dni_paciente        LIKE :busqueda
               OR telefono_paciente   LIKE :busqueda
               OR email_paciente      LIKE :busqueda
               OR usuario_paciente    LIKE :busqueda
        ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':busqueda' => '%' . $busqueda . '%'
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            /* return 'ERR_PACIENTE_03'; */
            die($e->getMessage());
        }
    }

    /**
     * Método para mostrar un paciente por su ID (Este método es para el formulario de modificación y eliminación)
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_paciente. ID del paciente a buscar
     * @return array|null Retorna un array asociativo con los datos del paciente o null en caso de no encontrarlo
     */
    public function mostrarPacientePorId(PDO $pdo, int $id_paciente): array|null
    {
        try {
            $sql = "SELECT * FROM paciente WHERE id_paciente = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id_paciente]);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Método para actualizar los datos de un paciente en la base de datos
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_paciente. ID del paciente a actualizar
     * @return string|int Retornamos el número de filas afectadas o ERR_PACIENTE_04 en caso de no poder actualizar el paciente
     */
    public function actualizarPaciente(PDO $pdo, int $id_paciente): bool
    {
        try {
            $sql = "
            UPDATE paciente SET
                dni_paciente        = :dni,
                nombre_paciente     = :nombre,
                apellidos_paciente  = :apellidos,
                telefono_paciente   = :telefono,
                email_paciente      = :email,
                usuario_paciente    = :usuario,
                foto_paciente       = :foto
            WHERE id_paciente = :id_paciente
        ";

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                ':dni'         => $this->dni_paciente,
                ':nombre'      => $this->nombre_paciente,
                ':apellidos'   => $this->apellidos_paciente,
                ':telefono'    => $this->telefono_paciente,
                ':email'       => $this->email_paciente,
                ':usuario'     => $this->usuario_paciente,
                ':foto'        => $this->foto_paciente,
                ':id_paciente' => $id_paciente
            ]);
        } catch (PDOException $e) {
            /* $error = 'ERR_PACIENTE_04'; // Error al actualizar paciente
            return $error; */
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Método para eliminar un paciente por su ID
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_paciente. ID del paciente a eliminar
     * @return int|string. Retorna el número de filas afectadas o 'ERR_ADMIN_05' en caso de error al eliminar paciente
     */
    public function eliminarPaciente(PDO $pdo, int $id_paciente): string|int
    {
        //Intentamos capturar errores del PDO
        try {
            //Consulta SQL para eliminar un paciente
            $sql = "DELETE FROM paciente WHERE id_paciente = :id_paciente";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_paciente' => $id_paciente
            ]);

            //Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();

            //Capturamos cualquier error de PDO
        } catch (PDOException $e) {
            //Devolvemos el mensaje de error
            $error = 'ERR_PACIENTE_05'; // Error al eliminar paciente
            return $error;
        }
    }
}
