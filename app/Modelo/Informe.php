<?php

/**
 * Método del módulo Informe
 *
 * Gestiona los registros, accesos, modificaciones y eliminciones de la BD
 *
 * @package Mediagend\App\Controlador
 */

namespace Mediagend\App\Modelo;

use PDO;
use PDOException;

/**
 * Clase Informe
 *
 * Representa un informe médico y proporciona métodos
 * para gestionar sus datos y operaciones en la base de datos.
 *
 * @package Mediagend\App\Modelo
 */
class Informe
{
    // Propiedades
    private ?int $id_informe = null;
    private ?int $id_clinica = null;
    private ?int $id_cita = null;
    private ?int $id_medico = null;
    private ?int $id_paciente = null;
    private ?string $diagnostico_informe = null;
    private ?string $tratamiento_informe = null;
    private ?string $fecha_generacion_informe = null;
    private ?string $archivo_pdf_informe = null;

    ///////////////////////////////////////////////   Getters   ///////////////////////////////////////////////////////////////
    /**
     * Método para obtener el ID del informe
     * @return int|null
     */
    public function getIdInforme(): ?int
    {
        return $this->id_informe;
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
     * Método para obtener el ID de la cita
     * @return int|null
     */
    public function getIdCita(): ?int
    {
        return $this->id_cita;
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
     * Método para optener el ID del paciente
     * @return int|null
     */
    public function getIdPaciente(): ?int
    {
        return $this->id_paciente;
    }
    /**
     * Método para obtener el diagnostico del informe
     * @return string|null
     */
    public function getDiagnosticoInforme(): ?string
    {
        return $this->diagnostico_informe;
    }
    /**
     * Método para obtener el tratamiento del informe
     * @return string|null
     */
    public function getTratamientoInforme(): ?string
    {
        return $this->tratamiento_informe;
    }
    /**
     * Método para obtener la fecha de creación del informe
     * @return string|null
     */
    public function getFechaGeneracionInforme(): ?string
    {
        return $this->fecha_generacion_informe;
    }
    /**
     * Método para obtener el Archivo PDF del informe
     * @return string|null
     */
    public function getArchivoPdfInforme(): ?string
    {
        return $this->archivo_pdf_informe;
    }

    ////////////////////////////////////////////////////   Setters   ////////////////////////////////////////////////////////////////
    /**
     * Método para establecer el id del informe
     * @param int $id_informe
     * @return void
     */
    public function setIdInforme(int $id_informe): void
    {
        $this->id_informe = $id_informe;
    }
    /**
     * Método para establecer el id de la clinica
     * @param int $id_clinica
     * @return void
     */
    public function setIdClinica(int $id_clinica): void
    {
        $this->id_clinica = $id_clinica;
    }
    /**
     * Método para establecer el id de la cita
     * @param mixed $id_cita
     * @return void
     */
    public function setIdCita(?int $id_cita): void
    {
        $this->id_cita = $id_cita;
    }
    /**
     * Método para establecer el id del médico
     * @param int $id_medico
     * @return void
     */
    public function setIdMedico(int $id_medico): void
    {
        $this->id_medico = $id_medico;
    }
    /**
     * Método para establecer el id del paciente
     * @param int $id_paciente
     * @return void
     */
    public function setIdPaciente(int $id_paciente): void
    {
        $this->id_paciente = $id_paciente;
    }
    /**
     * Método para establecer el diagnostico del informe
     * @param mixed $diagnostico
     * @return void
     */
    public function setDiagnosticoInforme(string $diagnostico): void
    {
        $this->diagnostico_informe = $diagnostico;
    }
    /**
     * Método para establecer el tratamineto del informe
     * @param string $tratamiento
     * @return void
     */
    public function setTratamientoInforme(string $tratamiento): void
    {
        $this->tratamiento_informe = $tratamiento;
    }
    /**
     * Método para obtener el archivo del informe
     * @param mixed $archivo
     * @return void
     */
    public function setArchivoPdfInforme(?string $archivo): void
    {
        $this->archivo_pdf_informe = $archivo;
    }

    // MÉTODOS DE BASE DE DATOS

    /**
     * Método para guardar un nuevo informe en la base de datos
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @return string|int Retronamos el número de filas afectadas o ERR_INFORME_01 en caso de no poder guardar el informe
     */
    public function guardarInforme(PDO $pdo): string|int
    {
        //Intentamos capturar errores del PDO
        try {
            //Consulta SQL para insertaar un nuevo informe
            $sql = "INSERT INTO informe 
                (id_clinica, id_cita, id_medico, id_paciente, diagnostico_informe, tratamiento_informe, archivo_pdf_informe)
                VALUES 
                (:id_clinica, :id_cita, :id_medico, :id_paciente, :diagnostico, :tratamiento, :archivo)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':id_clinica'  => $this->id_clinica,
                ':id_cita'     => $this->id_cita,
                ':id_medico'   => $this->id_medico,
                ':id_paciente' => $this->id_paciente,
                ':diagnostico' => $this->diagnostico_informe,
                ':tratamiento' => $this->tratamiento_informe,
                ':archivo'     => $this->archivo_pdf_informe
            ]);

            //Guardamos el ID autogenerado
            $this->id_informe = (int)$pdo->lastInsertId();

            // Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();

            // Capturamos cualquier error de PDO
        } catch (PDOException $e) {
            die('ERR_INFORME_01' . $e->getMessage()); // Error al guardar informe
        }
    }

    /**
     * Método para autenticar un informe en la base de datos
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param string $id_informe. ID del informe a mostrar
     * @return string|array|bool Retornamos un array con los datos del informe, false si no se encuentra o ERR_INFORME_02 en caso de error
     */
    public function mostrarInforme(PDO $pdo, ?int $id_informe = null): string|array|null
    {
        //Intentamos capturar errores del PDO
        try {
            if ($id_informe === null) {
                //Si no se indica el id, obtenemos Todos los informes
                $sql = "SELECT * FROM informe";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                //Consulta SQL para obtener el informe por ID
                $sql = "SELECT * FROM informe WHERE id_informe = :id_informe";
                //Preparamos y ejecutamos la consulta
                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    ':id_informe' => $id_informe
                ]);

                //Guardamos el resultado en un array asociativo
                $informe = $stmt->fetch(PDO::FETCH_ASSOC);

                //Retornamos el array o null si no existe
                return $informe ?: null;
            }
        } catch (PDOException $e) {
            die('ERR_INFORME_02' . $e->getMessage()); // Error al mostrar informe */
        }
    }
    /**
     * Método para listar los informes de un paciente
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_paciente. ID del paciente
     * @return array Retornamos un array con los informes del paciente o ERR_INFORME_03 si no se puede listar por paciente
     */
    public function listarPorPaciente(PDO $pdo, int $id_paciente): array
    {
        try {
            $sql = "SELECT id_informe, archivo_pdf_informe, fecha_generacion_informe
            FROM informe
            WHERE id_paciente = :id_paciente
            ORDER BY fecha_generacion_informe DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_paciente' => $id_paciente]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('ERR_INFORME_03' . $e->getMessage()); // Error al listar por paciente
        }
    }

    /**
     * Método para obtener un informe por su ID, incluyendo el ID de la clínica
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $idInforme. ID del informe
     * @return array|false Retornamos un array con los datos del informe, false si no se encuentra o ERR_INFORME_04 si no se puede listar por ID
     */
    public function listarPorId(PDO $pdo, int $idInforme)
    {
        try{
        $sql = "
        SELECT i.*, p.id_clinica
        FROM informe i
        INNER JOIN paciente p ON i.id_paciente = p.id_paciente
        WHERE i.id_informe = ?
        LIMIT 1
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idInforme]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            die('ERR_INFORME_04' . $e->getMessage()); // Error al listar por ID
        }
    }

    /**
     * Método para actualizar los datos del informe en la base de datos
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_informe. ID del informe a actualizar
     * @return string|int Retornamos el número de filas afectadas o ERR_INFORME_05 en caso de no poder actualizar el informe
     */
    public function actualizarInforme(PDO $pdo, int $id_informe): string|int
    {
        //Intentamos capturar errores de PDO
        try {
            //Consulta SQL para actualizar el informe
            $sql = "UPDATE informe SET
                        diagnostico_informe = :diagnostico,
                        tratamiento_informe = :tratamiento,
                        archivo_pdf_informe = :archivo
                    WHERE id_informe = :id_informe";

            //Preparamos y ejecutamos el informe
            $stmt = $pdo->prepare($sql);

            //Ejecutamos la consulta con los datos actualizados
            $stmt->execute([
                ':diagnostico' => $this->diagnostico_informe,
                ':tratamiento' => $this->tratamiento_informe,
                ':archivo'     => $this->archivo_pdf_informe,
                ':id_informe'  => $id_informe
            ]);

            //Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();
            //Capturamos errores de PDO
        } catch (PDOException $e) {
            die('ERR_INFORME_05' . $e->getMessage());//Error al actualizar informe
        }
    }

    /**
     * Método para eliminar un informe por su ID
     * @param PDO $pdo. Conexión PDO a la base de datos
     * @param int $id_informe. ID del informe a eliminar
     * @return int|string. Retorna el número de filas afectadas o 'ERR_INFORME_06' en caso de error al eliminar informe
     */
    public function eliminarInforme(PDO $pdo, int $id_informe): string|int
    {
        //Intentamos captuar errores del PDO
        try {
            //Consulta SQL para eliminar un informe
            $sql = "DELETE FROM informe WHERE id_informe = :id_informe";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_informe' => $id_informe
            ]);

            //Retornamos la cantidad de filas afectadas
            return $stmt->rowCount();
            //Capturamos el mensaje de error
        } catch (PDOException $e) {
            die('ERR_INFORME_06' . $e->getMessage());//Error al eliminar informe
        }
    }
}
