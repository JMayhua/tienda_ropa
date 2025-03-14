<?php
// aplicacion/controladores/ControladorPerfil.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../configuracion/config.php';

class ControladorPerfil {
    private $db;

    public function __construct() {
        global $db; // Usar la conexión global
        $this->db = $db;
    }

    // Método para mostrar el perfil del usuario
    public function verPerfil() {
        // Verificar si el usuario ha iniciado sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /Tienda_ropa/publico/index.php?accion=iniciar_sesion');
            exit;
        }

        // Obtener el ID del usuario
        $usuarioId = $_SESSION['usuario_id'];

        // Incluir el modelo de usuarios
        require_once __DIR__ . '/../modelos/ModeloUsuarios.php';
        $modeloUsuarios = new ModeloUsuarios();

        // Obtener los datos del usuario
        $usuario = $modeloUsuarios->obtenerPorId($usuarioId);

        // Pasar los datos a la vista
        require_once __DIR__ . '/../vistas/usuarios/ver_perfil.php';
    }

    // Método para mostrar el formulario de edición de perfil
    public function editarPerfil() {
        // Verificar si el usuario ha iniciado sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /Tienda_ropa/publico/index.php?accion=iniciar_sesion');
            exit;
        }

        // Obtener el ID del usuario
        $usuarioId = $_SESSION['usuario_id'];

        // Incluir el modelo de usuarios
        require_once __DIR__ . '/../modelos/ModeloUsuarios.php';
        $modeloUsuarios = new ModeloUsuarios();

        // Obtener los datos del usuario
        $usuario = $modeloUsuarios->obtenerPorId($usuarioId);

        // Pasar los datos a la vista
        require_once __DIR__ . '/../vistas/usuarios/editar_perfil.php';
    }

    // Método para actualizar el perfil del usuario
    public function actualizarPerfil() {
        // Verificar si el usuario ha iniciado sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /Tienda_ropa/publico/index.php?accion=iniciar_sesion');
            exit;
        }

        // Obtener el ID del usuario
        $usuarioId = $_SESSION['usuario_id'];

        // Validar los datos del formulario
        if (empty($_POST['nombre']) || empty($_POST['email'])) {
            $_SESSION['error'] = "Todos los campos son obligatorios.";
            header('Location: /Tienda_ropa/publico/index.php?accion=editar_perfil');
            exit;
        }

        // Incluir el modelo de usuarios
        require_once __DIR__ . '/../modelos/ModeloUsuarios.php';
        $modeloUsuarios = new ModeloUsuarios();

        // Actualizar los datos del usuario
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $resultado = $modeloUsuarios->actualizarUsuario($usuarioId, $nombre, $email);

        if ($resultado) {
            $_SESSION['exito'] = "Perfil actualizado correctamente.";
        } else {
            $_SESSION['error'] = "Hubo un error al actualizar el perfil.";
        }

        // Redirigir al perfil
        header('Location: /Tienda_ropa/publico/index.php?accion=ver_perfil');
        exit;
    }
}