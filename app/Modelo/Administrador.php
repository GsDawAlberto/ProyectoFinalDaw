<?php
namespace Mediagend\App\Modelo;

use PDO;
use PDOException;

class Administrador
{
    private ?int $id_admin = null;
    private ?string $nombre_admin = null;
    private ?string $email_admin = null;
    private ?string $usuario_admin = null;
    private ?string $password_admin = null;

    /* ============================
       GETTERS
       ============================ */
    public function getIdAdmin(): ?int {
        return $this->id_admin;
    }

    public function getNombreAdmin(): ?string {
        return $this->nombre_admin;
    }

    public function getEmailAdmin(): ?string {
        return $this->email_admin;
    }

    public function getUsuarioAdmin(): ?string {
        return $this->usuario_admin;
    }

    public function getPasswordAdmin(): ?string {
        return $this->password_admin;
    }

    /* ============================
       SETTERS
       ============================ */
    public function setIdAdmin(int $id_admin): void {
        $this->id_admin = $id_admin;
    }

    public function setNombreAdmin(string $nombre_admin): void {
        $this->nombre_admin = $nombre_admin;
    }

    public function setEmailAdmin(string $email_admin): void {
        $this->email_admin = $email_admin;
    }

    public function setUsuarioAdmin(string $usuario_admin): void {
        $this->usuario_admin = $usuario_admin;
    }

    public function setPasswordAdmin(string $password_admin): void {
        $this->password_admin = $password_admin;
    }

    /* ============================
       GUARDAR ADMINISTRADOR
       ============================ */
    public function guardarAdmin(PDO $pdo): bool
    {
        try {
            $sql = "INSERT INTO administrador 
                        (nombre_admin, email_admin, usuario_admin, password_admin) 
                    VALUES 
                        (:nombre_admin, :email_admin, :usuario_admin, :password_admin)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre_admin'   => $this->getNombreAdmin(),
                ':email_admin'    => $this->getEmailAdmin(),
                ':usuario_admin'  => $this->getUsuarioAdmin(),
                ':password_admin' => password_hash($this->getPasswordAdmin(), PASSWORD_BCRYPT)
            ]);

            // Guardamos el ID autogenerado
            $this->id_admin = intval($pdo->lastInsertId());

            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    /* ============================
       AUTENTICAR ADMINISTRADOR
       ============================ */
    public function autenticarAdmin(PDO $pdo, string $usuario_admin, string $password_admin): array|bool
    {
        try {
            $sql = "SELECT * FROM administrador WHERE usuario_admin = :usuario_admin";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':usuario_admin' => $usuario_admin]);

            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$admin) {
                return false; // usuario no existe
            }

            // Verificar contraseña
            if (!password_verify($password_admin, $admin['password_admin'])) {
                return false; // contraseña incorrecta
            }

            // Cargar datos dentro del objeto
            $this->id_admin      = $admin['id_admin'];
            $this->nombre_admin  = $admin['nombre_admin'];
            $this->email_admin   = $admin['email_admin'];
            $this->usuario_admin = $admin['usuario_admin'];

            return $admin;  // el controlador necesita el array

        } catch (PDOException $e) {
            return false;
        }
    }

    /* ============================
       OBTENER ADMINISTRADOR
       ============================ */
    public function obtenerAdmin(PDO $pdo, int $id_admin): ?array
    {
        try {
            $sql = "SELECT * FROM administrador WHERE id_admin = :id_admin";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_admin' => $id_admin]);

            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            return $admin ?: null;

        } catch (PDOException $e) {
            return null;
        }
    }

    /* ============================
       ACTUALIZAR ADMINISTRADOR
       ============================ */
    public function actualizarAdmin(PDO $pdo, int $id_admin): bool
    {
        try {
            $sql = "UPDATE administrador
                    SET nombre_admin  = :nombre_admin,
                        email_admin   = :email_admin,
                        usuario_admin = :usuario_admin,
                        password_admin = :password_admin
                    WHERE id_admin = :id_admin";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':nombre_admin'  => $this->nombre_admin,
                ':email_admin'   => $this->email_admin,
                ':usuario_admin' => $this->usuario_admin,
                ':password_admin' => password_hash($this->password_admin, PASSWORD_BCRYPT),
                ':id_admin'      => $id_admin
            ]);

            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    /* ============================
       ELIMINAR ADMINISTRADOR
       ============================ */
    public function eliminarAdmin(PDO $pdo, int $id_admin): bool
    {
        try {
            $sql = "DELETE FROM administrador WHERE id_admin = :id_admin";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_admin' => $id_admin]);

            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            return false;
        }
    }
}

