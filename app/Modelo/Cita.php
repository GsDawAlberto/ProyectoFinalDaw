<?php

namespace Mediagend\App\Modelo;

use PDO;
use PDOException;

class Cita
{
    // Propiedades
    private ?int $id_cita = null;
    private ?int $id_clinica = null;
    private ?int $id_paciente = null;
    private ?int $id_medico = null;
    private ?int $id_informe = null;
    private ?string $fecha_cita = null;
    private ?string $hora_cita = null;
    private ?string $estado_cita = null;
    private ?string $motivo_cita = null;

    // Getters
    /**
     * Método para obtener el ID de la cita
     * @return int|null
     */
    public function getIdCita(): ?int
    {
        return $this->id_cita;
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
     * Método para obtener el ID del paciente
     * @return int|null
     */
    public function getIdPaciente(): ?int
    {
        return $this->id_paciente;
    }

    /**
     * Método para obtener el ID del médico
     * @return int|null
     */
    public function getIdMedico(): ?int
    {
        return $this->id_medico;
    }

    /**
     * Método para obtener el ID del informe
     * @return int|null
     */
    public function getIdInforme(): ?int
    {
        return $this->id_informe;
    }

    /**
     * Método para obtener la fecha de la cita
     * @return string|null
     */
    public function getFechaCita(): ?string
    {
        return $this->fecha_cita;
    }

    /**
     * Método para obtener la hora de la cita
     * @return string|null
     */
    public function getHoraCita(): ?string
    {
        return $this->hora_cita;
    }

    /**
     * Método para obtener el estado de la cita
     * @return string|null
     */
    public function getEstadoCita(): ?string
    {
        return $this->estado_cita;
    }

    /**
     * Método para obtener el motivo de la cita
     * @return string|null
     */
    public function getMotivoCita(): ?string
    {
        return $this->motivo_cita;
    }

    // Setters
    /**
     * Método para establecer el ID de la cita
     * @param int $id_cita
     */
    public function setIdCita(int $id_cita): void
    {
        $this->id_cita = $id_cita;
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
     * Método para establecer el ID del paciente
     * @param int $id_paciente
     */
    public function setIdPaciente(int $id_paciente): void
    {
        $this->id_paciente = $id_paciente;
    }

    /**
     * Método para establecer el ID del médico
     * @param int $id_medico
     */
    public function setIdMedico(int $id_medico): void
    {
        $this->id_medico = $id_medico;
    }

    /**
     * Método para establecer el ID del informe
     * @param int|null $id_informe
     */
    public function setIdInforme(?int $id_informe): void
    {
        $this->id_informe = $id_informe;
    }

    /**
     * Método para establecer la fecha de la cita
     * @param string $fecha
     */
    public function setFechaCita(string $fecha): void
    {
        $this->fecha_cita = $fecha;
    }

    /**
     * Método para establecer la hora de la cita
     * @param string $hora
     */
    public function setHoraCita(string $hora): void
    {
        $this->hora_cita = $hora;
    }

    /**
     * Método para establecer el estado de la cita
     * @param string $estado
     */
    public function setEstadoCita(string $estado): void
    {
        $this->estado_cita = $estado;
    }

    /**
     * Método para establecer el motivo de la cita
     * @param string|null $motivo
     */
    public function setMotivoCita(?string $motivo): void
    {
        $this->motivo_cita = $motivo;
    }

    // MÉTODOS DE BASE DE DATOS

    /**
     * Método para guardar una nueva cita en la base de datos
     * @param PDO $pdo Conexión PDO a la base de datos
     * @return string|int Retorna el número de filas afectadas o ERR_CITA_01 en caso de no poder guardar cita
     */
    public function guardarCita(PDO $pdo): string|int
    {
        // Intentamos capturar errores del PDO
        try {
            //Consulta SQL para insertar una nueva cita
            $sql = "INSERT INTO cita 
                (id_clinica, id_paciente, id_medico, id_informe, fecha_cita, hora_cita, estado_cita, motivo_cita)
                VALUES 
                (:id_clinica, :id_paciente, :id_medico, :id_informe, :fecha, :hora, :estado, :motivo)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_clinica'  => $this->id_clinica,
                ':id_paciente' => $this->id_paciente,
                ':id_medico'   => $this->id_medico,
                ':id_informe'  => $this->id_informe,
                ':fecha'       => $this->fecha_cita,
                ':hora'        => $this->hora_cita,
                ':estado'      => $this->estado_cita ?? 'pendiente',
                ':motivo'      => $this->motivo_cita
            ]);
            //Guardamos el ID autogenerado
            $this->id_cita = (int)$pdo->lastInsertId();

            // Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();

            // Capturamos cualquier error de PDO
        } catch (PDOException $e) {
            $error =  'ERR_CITA_01'; // Error al guardar cita
            return $error;
        }
    }

    /**
     * Método para mostrar una cita por su ID o todas las citas
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int|null $id_cita. ID de la cita a mostrar
     * @return string|array|null Retornamos un array con los datos de la cita, false si no se encuentra o ERR_CITA_02 en caso de error
     */
    public function mostrarCita(PDO $pdo, ?int $id_cita = null): string|array|null
    {
        //Intentamos capturar errores del PDO
        try {
            if ($id_cita === null) {
                //Si no se indica el id, obtenemos Todos los informes
                $sql = "SELECT * FROM cita";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                //Consulta SQL para obtener la cita por ID
                $sql = "SELECT * FROM cita WHERE id_cita = :id_cita";
                //Preparamos y ejecutamos la consulta
                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    ':id_cita' => $id_cita
                ]);

                //Guardamos el resultado en un array asociativo
                $cita = $stmt->fetch(PDO::FETCH_ASSOC);

                //Retornamos el array o null si no existe
                return $cita ?: null;
            }
        } catch (PDOException $e) {
            return 'ERR_CITA_02'; //Error al mostar cita
        }
    }

    /**
     * Método para actualizar los datos de la cita en la base de datos
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_cita. ID de la cita a actualizar
     * @return string|int Retornamos el número de filas afectadas o ERR_CITA_04 en caso de no poder actualizar la cita
     */
    public function actualizarCita(PDO $pdo, int $id_cita): string|int
    {
        // Intentamos capturar errores de PDO
        try {
            // Consulta SQL para actualizar la cita
            $sql = "UPDATE cita SET
                        fecha_cita = :fecha,
                        hora_cita = :hora,
                        estado_cita = :estado,
                        motivo_cita = :motivo,
                        id_informe = :id_informe
                    WHERE id_cita = :id_cita";

            // Preparamos y ejecutamos la cita
            $stmt = $pdo->prepare($sql);

            //Ejecutamos la consulta con los datos actualizados
            $stmt->execute([
                ':fecha'      => $this->fecha_cita,
                ':hora'       => $this->hora_cita,
                ':estado'     => $this->estado_cita,
                ':motivo'     => $this->motivo_cita,
                ':id_informe' => $this->id_informe,
                ':id_cita'    => $id_cita
            ]);

            //Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();
            //Capturamos errores de PDO
        } catch (PDOException $e) {
            $error = 'ERR_CITA_04'; //Error al actualizar cita
            return $error;
        }
    }

    /**
     * Método para eliminar una cita por su ID
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_cita. ID de la cita a eliminar
     * @return int|string. Retorna el número de filas afectadas o 'ERR_CITA_05' en caso de error al eliminar la cita
     */
    public function eliminarCita(PDO $pdo, int $id_cita): string|int
    {
        //Intentamos captuara errores del PDO
        try {
            //Consulta SQL para eliminar una cita
            $sql = "DELETE FROM cita WHERE id_cita = :id_cita";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_cita' => $id_cita
            ]);
            //Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();
            //Capturamos el mensaje de error
        } catch (PDOException $e) {
            $error =  'ERR_CITA_05'; //Error al eliminar la cita
            return $error;
        }
    }
}
