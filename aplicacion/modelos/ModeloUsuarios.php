<?php
// aplicacion/modelos/ModeloUsuarios.php
require_once __DIR__ . '/../../configuracion/config.php';

class ModeloUsuarios {
    private $db;

    public function __construct() {
        global $db; // Usar la conexión global
        $this->db = $db;
    }

    /**
     * Registrar un nuevo usuario.
     *
     * @param string $nombre Nombre del usuario.
     * @param string $email Email del usuario.
     * @param string $contrasena Contraseña del usuario.
     * @param string $rol Rol del usuario (por defecto: 'cliente').
     * @return bool True si se registró correctamente, false en caso contrario.
     */
    public function registrarUsuario($nombre, $email, $contrasena, $rol = 'cliente') {
        try {
            // Verificar si el email ya está registrado
            $query = "SELECT id FROM usuarios WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['error'] = "El email ya está registrado.";
                return false;
            }

            // Hash de la contraseña
            $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario
            $query = "INSERT INTO usuarios (nombre, email, contrasena, rol) VALUES (:nombre, :email, :contrasena, :rol)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':contrasena' => $contrasenaHash,
                ':rol' => $rol
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            $_SESSION['error'] = "Error al registrar el usuario. Inténtalo de nuevo.";
            return false;
        }
    }

    /**
     * Obtener todos los usuarios.
     *
     * @return array Lista de usuarios.
     */
    public function obtenerUsuarios() {
        try {
            $query = "SELECT * FROM usuarios";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un usuario por su ID.
     *
     * @param int $id ID del usuario.
     * @return array|false Datos del usuario o false si no se encuentra.
     */
    public function obtenerUsuarioPorId($id) {
        try {
            $query = "SELECT * FROM usuarios WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar un usuario.
     *
     * @param int $id ID del usuario.
     * @param string $nombre Nombre del usuario.
     * @param string $email Email del usuario.
     * @param string $rol Rol del usuario.
     * @return bool True si se actualizó correctamente, false en caso contrario.
     */
    public function actualizarUsuario($id, $nombre, $email, $rol) {
        try {
            $query = "UPDATE usuarios SET nombre = :nombre, email = :email, rol = :rol WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':nombre' => $nombre,
                ':email' => $email,
                ':rol' => $rol
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar un usuario.
     *
     * @param int $id ID del usuario.
     * @return bool True si se eliminó correctamente, false en caso contrario.
     */
    public function eliminarUsuario($id) {
        try {
            $query = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Contar el número total de usuarios.
     *
     * @return int Número total de usuarios.
     */
    public function contarUsuarios() {
        try {
            $query = "SELECT COUNT(*) AS total FROM usuarios";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch (PDOException $e) {
            error_log("Error al contar usuarios: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Verificar las credenciales de un usuario.
     *
     * @param string $email Email del usuario.
     * @param string $contrasena Contraseña del usuario.
     * @return array|false Datos del usuario si las credenciales son válidas, false en caso contrario.
     */
    public function verificarCredenciales($email, $contrasena) {
        try {
            $query = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
                return $usuario;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error al verificar credenciales: " . $e->getMessage());
            return false;
        }
    }
}